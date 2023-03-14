<?php

namespace App\Service;

use App\Entity\Question;
use App\Entity\Result;
use App\Entity\User;
use App\Entity\Variant;
use Doctrine\ORM\EntityManagerInterface;

class QuestionHandler
{
    const SHUFFLED = 'shuffled';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly SessionService         $sessionService,
        private readonly ResultService          $resultService)
    {
    }

    public function handle(array $testData, User $user = null): array
    {
        $response = [];

        if ($user) {
            $result = new Result();
        }
        foreach ($testData as $answerData) {
            $question = $this->entityManager->find(Question::class, $answerData["id"]);

            if ($question) {
                $score = $this->getQuestionScore($question, $answerData["answer"]);
                $answer = [
                    "id" => $answerData["id"],
                    "title" => $question->getTitle(),
                    "variant" => isset($this->sessionService->get(self::SHUFFLED)[$question->getId()]) ? $this->sessionService->get(self::SHUFFLED)[$question->getId()]['variant'] : $this->getVariantsToArray($question),
                    "result" => [
                        "score" => $score,
                    ],
                    "type" => $question->getType()->getTitle()
                ];
                if ($user) {

                    $this->resultService->save($user, $question, $this->getShuffledUserAnswers($question, $answerData["answer"], $this->sessionService->get(self::SHUFFLED)[$question->getId()] ?? ['variant' => $this->getVariantsToArray($question)]), $score, $result);
                    $answer["result"] += [
                        "true_answer" => $this->getShuffledTrueAnswers($question, $this->sessionService->get(self::SHUFFLED)[$question->getId()] ?? []),
                        "user_answer" => $answerData["answer"],
                    ];
                }

                if (!empty($question->getSubTitle())) {
                    $answer["subTitle"] = $this->sessionService->get(self::SHUFFLED)[$question->getId()]['subTitle'] ?? [];
                }

            } else {
                $answer = [
                    "id" => $answerData["id"],
                    "error" => "question not found"
                ];
            }
            $response[] = $answer;
        }
        return $response;

    }

    public function getPreparedQuestions(array $questions): array
    {
        $questionsInfo = [];
        foreach ($questions as $question) {

            $variants = $this->getVariantsToArray($question);
            shuffle($variants);
            $subtitles = $question->getSubTitle();
            shuffle($subtitles);

            $questionsInfo[] = [
                'id' => $question->getId(),
                'title' => $question->getTitle(),
                'type' => $question->getType()->getTitle(),
                'subTitle' => $subtitles,
                'variant' => $variants,
            ];
        }
        return $questionsInfo;
    }


    private function getShuffledUserAnswers(Question $question, array $userAnswer, array $shuffled): array
    {
        $answers = [];
        if ($question->getVariant()->count() > 0) {
            foreach ($userAnswer as $answer) {
                if (isset($answer, $this->getVariantsToArray($question)[$answer])) {
                    $variant = $this->entityManager->getRepository(Variant::class)->findOneByQuestionAndTitle($question->getId(), $shuffled['variant'][$answer]);
                    $answers[] = $variant->getId();

                }
            }
        } else {
            $answers = $userAnswer;
        }
        if (count($question->getSubTitle()) > 1 && isset($shuffled['subTitle'])) {
            $shuffledUserAnswer = [];
            foreach ($question->getSubTitle() as $subTitle) {
                if (isset($answers[array_search($subTitle, $shuffled['subTitle'])])) {
                    $shuffledUserAnswer[] = $answers[array_search($subTitle, $shuffled['subTitle'])];

                }
            }
            $answers = $shuffledUserAnswer;
        }
        return $answers;
    }

    private function getShuffledTrueAnswers(Question $question, array $shuffled): array
    {
        $trueShufflesAnswers = [];
        if (isset($shuffled['variant']) && count($shuffled['variant']) > 0) {
            foreach ($question->getAnswer() as $answer) {

                $variant = $this->entityManager->getRepository(Variant::class)->find($answer);
                if (!is_null($variant)) {
                    $trueShufflesAnswers[] = array_search($variant->getTitle(), $shuffled['variant']);
                }

            }
        } else if ($question->getVariant()->count() > 0) {
            foreach ($question->getAnswer() as $answer) {

                $variant = $this->entityManager->getRepository(Variant::class)->find($answer);
                if (!is_null($variant)) {
                    $trueShufflesAnswers[] = array_search($variant->getTitle(), $this->getVariantsToArray($question));
                }
            }
        } else {
            $trueShufflesAnswers = $question->getAnswer();
        }
        if (isset($shuffled['subTitle']) && count($shuffled['subTitle']) > 1) {

            $answers = [];
            foreach ($shuffled['subTitle'] as $subTitle) {
                $answers[] = $trueShufflesAnswers[array_search($subTitle, $question->getSubTitle())];
            }

            $trueShufflesAnswers = $answers;

        }
        return $trueShufflesAnswers;

    }

    private function getQuestionScore(Question $question, array $answerData): bool
    {
        $score = false;
        $userAnswers = $this->answerPrepare($answerData);
        if (isset($this->sessionService->get(self::SHUFFLED)[$question->getId()])) {
            $userShuffledAnswers = $this->getShuffledUserAnswers($question, $userAnswers, $this->sessionService->get(self::SHUFFLED)[$question->getId()]);

        } else {
            $userShuffledAnswers = $this->getShuffledUserAnswers($question, $userAnswers, ['variant' => $this->getVariantsToArray($question)]);
        }
        switch ($question->getType()->getTitle()) {
            case 'radio':
            case 'order':
            case 'input_one':
                if ($userShuffledAnswers == $question->getAnswer()) {
                    $score = true;
                };
                break;
            case 'conformity':
                if ($userShuffledAnswers === $question->getAnswer()) {
                    $score = true;
                };
                break;
            case 'checkbox':
            case 'checkbox_picture':

                if (count(array_diff($question->getAnswer(), $userShuffledAnswers)) === 0) {
                    $score = true;
                }
                break;
//            case 'input_many':
//            case 'blank':

        }
        return $score;
    }

    private function answerPrepare(array $answers): array
    {
        return array_map(function ($answer) {
            return mb_strtolower(trim($answer));
        }, $answers);
    }

    private function getVariantsToArray(Question $question): array
    {

        if ($question->getVariant()->count() > 0) {
            return array_map(function ($variant) {
                return $variant->getTitle();
            }, $question->getVariant()->toArray());
        }
        return [];

    }

    public function prepareForSession(array $questions): array
    {
        $response = [];

        if (count($questions) > 0) {
            foreach ($questions as $question) {
                $variants = [];
                foreach ($question->getVariant() as $variant){
                    $variants[]= $variant->getTitle();
                }
                $response[$question->getId()] = [
                    "subTitle" => $question->getSubtitle(),
                    "variant" => $variants
                ];
            }

        }
        return $response;
    }


}