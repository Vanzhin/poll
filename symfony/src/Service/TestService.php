<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Question;
use App\Entity\Test;
use App\Entity\User;
use App\Factory\Answer\AnswerFactory;
use App\Factory\Result\ResultFactory;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

class TestService
{
    public function __construct(private readonly EntityManagerInterface $em,
                                private readonly ResultFactory          $resultFactory,
                                private readonly ValidationService      $validation,
                                private readonly QuestionHandler        $questionHandler,
                                private readonly AnswerFactory          $answerFactory,
                                private readonly SessionService         $sessionService,
    )
    {
    }

    public function make(Test $test, array $data): Test
    {
        foreach ($data as $key => $item) {
            if ($key === 'title') {
                $test->setTitle($item);
                continue;
            };
            if ($key === 'description') {
                $test->setDescription($item);
                continue;

            };
            if ($key === 'category' && $this->em->find(Category::class, $item)) {
                $test->setCategory($this->em->find(Category::class, $item));

            };

        }

        return $test;
    }

    public function handle(array $data, User $user = null): array
    {
        $response = [];
        $response['response'] = [];
        $score = 0;
        $response['status'] = 200;
        if ($user) {
            $result = $this->resultFactory->createBuilder()->buildResult($data['info'] ?? [], $user);
            $errors = $this->validation->validate($result);
            if (count($errors) > 0) {
                return [
                    'response' => [
                        'message' => 'Ошибка при вводе данных',
                        'error' => $errors
                    ],
                    'status' => 422
                ];

            } else {
                $this->em->persist($result);
                $this->em->flush();
            }

        } else {
            $result = null;

        }
        foreach ($data['question'] ?? [] as $answerData) {
            $question = $this->em->find(Question::class, $answerData["id"]);
            if ($question) {
                $preparedQuestion = $this->questionHandler->handle($answerData, $question);
                $response['response'][] = $preparedQuestion;

                //todo начисление очков
                if($preparedQuestion->getResult()['correct']?? null){
                    $score++;
                }

                if ($result) {
                    $content = (isset($this->sessionService->get(QuestionHandler::SHUFFLED)[$question->getId()]) ? $this->sessionService->get(QuestionHandler::SHUFFLED)[$question->getId()] : null);
                    $answer = $this->answerFactory->createBuilder()->buildAnswer($this->questionHandler->getShuffledUserAnswers($question, $answerData['answer'], $content ?? ['variant' => $this->questionHandler->getVariantsToArray($question)]), $question, $result);
                    $this->em->persist($answer);
                    $this->em->flush();

                    $question->setResult(array_merge($question->getResult(), [
                            "true_answer" => $this->questionHandler->getShuffledTrueAnswers($question, $content ?? []),
                            "user_answer" => $answerData["answer"]
                        ])
                    );

                }

            } else {
                $response['response'][] = [
                    "id" => $answerData["id"],
                    "error" => "question not found"
                ];
            }

        }
        if ($result){
            $result->setScore($score);
            $this->em->persist($result);
            $this->em->flush();
        }

        return $response;

    }

    public function getQuestionForResponse(array $questions): array
    {
        foreach ($questions as $question) {
            $variants = $question->getVariant()->toArray();
            shuffle($variants);
            $subtitles = $question->getSubTitles()->toArray();
            shuffle($subtitles);
            $question->getSubtitles()->clear();
            $question->getVariant()->clear();

            foreach ($variants as $variant) {
                $question->addVariant($variant);
            }
            foreach ($subtitles as $subtitle) {
                $question->addSubtitle($subtitle);
            }
        }

        return $questions;

    }
}