<?php

namespace App\Service;

use App\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;

class QuestionService
{

    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function handle(array $answerData)
    {
        $question = $this->entityManager->find(Question::class, $answerData["id"]);
        if ($question) {
            return [
                "id" => $answerData["id"],
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

        switch ($question->getType()->getTitle()) {
            case 'radio':
                if (array_key_exists($answerData[0], $question->getVariant())) {
                    $score = (int)$answerData[0] === (int)$question->getAnswer()[0];
                }

                break;
            case 'checkbox':
                if (count(array_diff($question->getAnswer(), $answerData)) === 0) {
                    $score = true;
                }
                break;
//            case 'input_one':
//                $variants = [];
//                $question->setTitle($this->faker->realTextBetween(10, 15) . static::BLANK_FIELD . $this->faker->realTextBetween(10, 15) . '?')
//                    ->setVariant($variants)
//                    ->setAnswer([$this->faker->word()])
//                    ->addTicket($this->getRandomReference(Ticket::class));
//                break;
//            case 'input_many':
//            case 'blank':
//                $answers = $this->faker->words($this->faker->numberBetween(2, 5));
//                $title = $this->faker->realTextBetween(10, 50) . '?' . static::QUESTION_SEPARATOR;
//                $inputs = [];
//                foreach ($answers as $answer) {
//                    $inputs[] = $this->faker->realTextBetween(10, 35) . '?' . static::BLANK_FIELD;
//                }
//
//                $question->setTitle($title . implode('', $inputs))
//                    ->setVariant([])
//                    ->setAnswer($answers)
//                    ->addTicket($this->getRandomReference(Ticket::class));
//                break;
//            case 'conformity':
//                $variants = $this->faker->words($this->faker->numberBetween(2, 10));
//                $answers = $this->faker->randomElements($variants, $this->faker->numberBetween(2, count($variants)), true);
//                $title = $this->faker->realTextBetween(10, 50) . '?' . static::QUESTION_SEPARATOR;
//                $inputs = [];
//                foreach ($answers as $answer) {
//                    $inputs[] = $this->faker->realTextBetween(10, 35) . '?' . static::BLANK_FIELD;
//                }
//                $question->setTitle($title . implode('', $inputs))
//                    ->setVariant($variants)
//                    ->setAnswer($answers)
//                    ->addTicket($this->getRandomReference(Ticket::class));
//                break;
//            case 'order':
//                $variants = $this->faker->words($this->faker->numberBetween(2, 5));
//                $answers = $variants;
//                shuffle($answers);
//                $title = $this->faker->realTextBetween(10, 50);
//                $question->setTitle($title)
//                    ->setVariant($variants)
//                    ->setAnswer($answers)
//                    ->addTicket($this->getRandomReference(Ticket::class));
//                break;
//            case 'checkbox_picture':
//                $variants = $this->faker->words($this->faker->numberBetween(2, 5));
//                $pictures = [];
//                foreach ($variants as $variant) {
//                    $pictures[] = static::PICTURE_MARK . 'picture' . $this->faker->numberBetween(1, 10) . '.jpeg' . static::PICTURE_MARK;
//                }
//                $question->setTitle($this->faker->realTextBetween(30, 255) . '?' . static::QUESTION_SEPARATOR . implode('', $pictures))
//                    ->setVariant($variants)
//                    ->setAnswer($this->faker->randomElements($variants, $this->faker->numberBetween(1, count($variants))))
//                    ->addTicket($this->getRandomReference(Ticket::class));
//                break;
//            case 'textarea':
//                $question->setTitle($this->faker->realTextBetween(10, 50) . '?')
//                    ->setVariant([])
//                    ->setAnswer([])
//                    ->addTicket($this->getRandomReference(Ticket::class));
//                break;
        }
        return $score;
    }
}