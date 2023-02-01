<?php

namespace App\Service;

use App\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;

class QuestionService
{

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function handle(array $answerData): array
    {
        $question = $this->entityManager->find(Question::class, $answerData["id"]);
        if ($question) {
            return [
                "id" => $answerData["id"],
                "title" => $question->getTitle(),
                "variant" => $question->getVariant(),
                "result" => [
                    "true_answer" => $question->getAnswer(),
                    "user_answer" => $answerData["answer"],
                    "score" => $this->score($question, $answerData["answer"])


                ]

            ];
        }
        return [
            "id" => $answerData["id"],
            "error" => "question not found"
        ];


//     получаю
//     {"id":"1001", "answer":["quia","odio","aut","unde"]}
//     отдать
//     {"id":"1001", "variant":["quia","odio","aut","unde"], "result":{"true_answer": ["odio"], "user_answer":["unde"], "score": "0" }}


    }

    private function score(Question $question, array $answerData): bool
    {
        $score = false;
        $userAnswers = $this->answerPrepare($answerData);
        $questionAnswers = $this->answerPrepare($question->getAnswer());
        $questionVariants = $this->answerPrepare($question->getVariant());

        switch ($question->getType()->getTitle()) {
            case 'radio':
                if (array_key_exists($userAnswers[0], $questionVariants)) {
                    $score = (int)$userAnswers[0] === (int)$questionAnswers[0];
                }

                break;
            case 'checkbox':
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

//            case 'textarea':
//                $question->setTitle($this->faker->realTextBetween(10, 50) . '?')
//                    ->setVariant([])
//                    ->setAnswer([])
//                    ->addTicket($this->getRandomReference(Ticket::class));
//                break;
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