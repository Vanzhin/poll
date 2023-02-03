<?php

namespace App\Service;

use App\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;

class QuestionService
{
    const SHUFFLED = 'shuffled';

    public function __construct(private readonly EntityManagerInterface $entityManager, private readonly SessionService $sessionService)
    {
    }

    public function handle(array $answerData): array
    {
        try {
            $question = $this->entityManager->find(Question::class, $answerData["id"]);
            if ($question) {
                $response = [
                    "id" => $answerData["id"],
                    "title" => $question->getTitle(),
                    "variant" => $question->getVariant(),
                    "result" => [
                        "true_answer" => $this->getShuffledAnswers($question),
                        "user_answer" => $answerData["answer"],
                        "score" => $this->getQuestionScore($question, $answerData["answer"])
                    ],
                    "type" => $question->getType()
                ];
                if (!empty($question->getSubTitle())) {
                    $response["subTitle"] = [$question->getSubTitle()];
                }

            } else {
                $response = [
                    "id" => $answerData["id"],
                    "error" => "question not found"
                ];
            }

        } catch (\Exception $e) {
            $response = ["error" => $e->getTrace()];
        } finally {
            $this->sessionService->remove(self::SHUFFLED);
            return $response;
        }

    }

    public function shuffleVariants(array $questions): array
    {
        foreach ($questions as $question) {
            if (count($question->getVariant()) > 1) {
                $variants = $question->getVariant();
                shuffle($variants);
                $question->setVariant($variants);

                if (count($question->getSubTitle()) > 1) {
                    $this->shuffleSubTitle($question);
                }
                $this->sessionService->add([$question->getId() => ["variant" => $question->getVariant(), "subTitle" => $question->getSubtitle()]], self::SHUFFLED);
            }


        }
        return $questions;
    }

    public function shuffleSubTitle(Question $question): Question
    {
        $subTitle = $question->getSubTitle();
        shuffle($subTitle);
        $question->setSubTitle($subTitle);

        return $question;
    }

    private function getShuffledAnswers(Question $question, string $name = self::SHUFFLED): array
    {
//        $testSession = [
//            6885 => ["sunt", "doloremque", "ipsa", "sint"],
//            6321 => ["eum", "at", "reiciendis"],
//            6514 => ["et", "debitis", "molestiae", "debitis", "recusandae"]
//        ];
//        todo доработать если ответ в виде строки приходит типа input_many
        if ($this->sessionService->get($name) && array_key_exists($question->getId(), $this->sessionService->get($name))) {
//        if (array_key_exists($question->getId(), $testSession)) {

            $shuffledVariants = $this->sessionService->get($name)[$question->getId()]['variant'];
            $shuffledSubTitles = $this->sessionService->get($name)[$question->getId()]['subTitle'];

//            $shuffledVariants = $testSession[$question->getId()];

            $answers = [];
            foreach ($question->getAnswer() as $answer) {
                $answers[] = array_search($question->getVariant()[$answer], $shuffledVariants);
            }
            if (count($shuffledSubTitles) > 1) {
                $answersWithSubTitle = [];
                foreach ($shuffledSubTitles as $subTitle) {

                    $answersWithSubTitle[] = $answers[array_search($subTitle, $question->getSubTitle())];

                }
                $answers = $answersWithSubTitle;

            }

        } else {
            $answers = $question->getAnswer();
        }
        return $answers;
    }

    private function getQuestionScore(Question $question, array $answerData): bool
    {
        $score = false;
        $userAnswers = $this->answerPrepare($answerData);
        $questionAnswers = $this->answerPrepare($this->getShuffledAnswers($question));
        $questionVariants = $this->answerPrepare($question->getVariant());

        switch ($question->getType()->getTitle()) {
            case 'radio':
                if (array_key_exists($userAnswers[0], $questionVariants)) {
                    $score = (int)$userAnswers[0] === (int)$questionAnswers[0];
                }

                break;
            case 'checkbox':
                if (count(array_diff($userAnswers, $questionAnswers)) === 0) {
                    $score = true;
                }
                break;
            case 'checkbox_picture':
            case 'input_many':
            case 'blank':
            case 'conformity':
            case 'order':

                if (count(array_diff_assoc($questionAnswers, $userAnswers)) === 0) {
                    $score = true;
                }
                break;
            case 'input_one':

                if (in_array($userAnswers[0], $questionAnswers)) {
                    $score = true;
                }
                break;

        }
        return $score;
    }

    private function answerPrepare(array $answers): array
    {
        return array_map(function ($answer) {
            return mb_strtolower(trim($answer));
        }, $answers);
    }

}