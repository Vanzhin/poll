<?php

namespace App\Service;

use App\Entity\Question;
use App\Entity\Subtitle;
use App\Entity\Variant;
use Doctrine\ORM\EntityManagerInterface;

class QuestionHandler
{
    const SHUFFLED = 'shuffled';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly SessionService         $sessionService,
    )
    {
    }

    public function handle(array $answerData, Question $question): Question
    {

        $score = $this->getQuestionScore($question, $answerData["answer"]);
        $question->setResult([
            "correct" => $score,
        ]);
        $variantTitles = isset($this->sessionService->get(self::SHUFFLED)[$question->getId()]) ? $this->sessionService->get(self::SHUFFLED)[$question->getId()]['variant'] : $this->getVariantsToArray($question);
        $question->getVariant()->clear();
        foreach ($variantTitles as $titles) {
            $variant = $this->entityManager->getRepository(Variant::class)->findOneByQuestionAndTitle($question->getId(), $titles);
            if ($variant) {
                $question->addVariant($variant);
            }
        };

        if ($question->getSubtitles()->count() > 0 && count($this->sessionService->get(self::SHUFFLED)[$question->getId()]['subTitle'] ?? []) > 0) {
            $question->getSubtitles()->clear();
            foreach ($this->sessionService->get(self::SHUFFLED)[$question->getId()]['subTitle'] ?? [] as $subtitleId) {
                $question->addSubtitle($this->entityManager->find(Subtitle::class, $subtitleId));
            }
        }

        return $question;

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


    public function getShuffledUserAnswers(Question $question, array $userAnswer, array $shuffled): array
    {
        $answers = [];
        if ($question->getVariant()->count() > 0) {
            foreach ($userAnswer as $key => $answer) {
                if (isset($answer, $this->getVariantsToArray($question)[$answer])) {
                    $variant = $this->entityManager->getRepository(Variant::class)->findOneByQuestionAndTitle($question->getId(), $shuffled['variant'][$answer]);
                    $answers[] = $variant->getId();

                } else {
                    $answers[] = null;

                }
                if ($question->getType()->getTitle() === 'input_one') {
                    $answers = [];
                    $variant = $this->entityManager->getRepository(Variant::class)->findOneByQuestionAndTitle($question->getId(), $answer);
                    if ($variant) {
                        $answers[] = $variant->getId();

                    } else {
                        $answers[] = $answer;

                    }
                }
            }
        } else {
            $answers = $userAnswer;
        }
        if ($question->getSubtitles()->count() > 1 && isset($shuffled['subTitle'])) {
            $shuffledUserAnswer = [];
            foreach ($question->getSubtitles() as $subTitle) {
                if (isset($answers[array_search($subTitle->getId(), $shuffled['subTitle'])])) {
                    $shuffledUserAnswer[] = $answers[array_search($subTitle->getId(), $shuffled['subTitle'])];

                } else {
                    $shuffledUserAnswer[] = null;
                }
            }
            $answers = $shuffledUserAnswer;
        }
        return $answers;
    }

    public function getShuffledTrueAnswers(Question $question, array $shuffled): array
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
            foreach ($shuffled['subTitle'] as $subTitleId) {
                $answers[] = $trueShufflesAnswers[array_search($this->entityManager->find(Subtitle::class, $subTitleId), $question->getSubtitles()->toArray())];

            }

            $trueShufflesAnswers = $answers;

        }

        return $trueShufflesAnswers;

    }

    public function getQuestionScore(Question $question, array $answerData): bool
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

    public function getVariantsToArray(Question $question): array
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
                $subtitles = [];

                foreach ($question->getVariant() as $variant) {
                    $variants[] = $variant->getTitle();
                }
                foreach ($question->getSubtitles() as $subtitle) {
                    $subtitles[] = $subtitle->getId();
                }
                $response[$question->getId()] = [
                    "subTitle" => $subtitles,
                    "variant" => $variants
                ];
            }
        }
        return $response;
    }

    public function isAnswerCorrect(Question $question, array $answerContent): bool
    {
        $correct = false;


        if ($question->getAnswer() === $question->getAnswer()) {
            $correct = true;
        };

        return $correct;
    }


}