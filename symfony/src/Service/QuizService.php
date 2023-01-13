<?php

namespace App\Service;

use App\Entity\Quiz;
use Symfony\Component\HttpFoundation\RequestStack;

class QuizService
{

    const NAME = 'quiz';
    const SAVE = 'quiz_to_save';
    private static int $current;

    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    public function updateOrCreate(int $id): void
    {
        $session = $this->requestStack->getSession();
        $data = $session->get(static::NAME, []);

        if (isset($data[$id])) {
            self::$current = count($data[$id]);
        } else {
            self::$current = 0;
            $session->set(static::NAME, [
                $id => [
                ]
            ]);
        }

    }

    public function answerHandle(int $quizId): void
    {
        $request = $this->requestStack->getCurrentRequest();
        $session = $this->requestStack->getSession();
        $data = $session->get(static::NAME, []);

        $question_id = $request->request->get('question_id');
        foreach ($request->request->all() as $key => $userAnswer) {
            if ($key !== 'question_id') {
                $data[$quizId][$question_id][] = $userAnswer;
            }

        }
        $this->setCurrent($data[$quizId]);
        $session->set(static::NAME, $data);
    }

    public function getCurrent(): int
    {
        return self::$current;
    }

    private function setCurrent(array $array): void
    {
        self::$current = count($array);

    }

    public function getScore(Quiz $quiz): int
    {
        $session = $this->requestStack->getSession();
        $userAnswers = $session->get(static::NAME, [])[$quiz->getId()];
        $score = 0;
        if ($userAnswers) {
            foreach ($quiz->getQuestion() as $question) {
                if ($question->getAnswer() === $userAnswers[$question->getId()]) {
                    $score++;
                }
            }
        }
        return $score;

    }
    public function setUserAnswer(Quiz $quiz): Quiz
    {
        $session = $this->requestStack->getSession();
        $answers = $session->get(static::NAME, [])[$quiz->getId()];
        if ($answers){
            foreach ($quiz->getQuestion() as $question) {
                $quiz->removeQuestion($question);
                $question->setCurrentAnswer($answers[$question->getId()]);
                $quiz->addQuestion($question);
            }
        }

        return $quiz;
    }

    public function addQuizToSave(int $quizId): void
    {
        $session = $this->requestStack->getSession();
        $quizzesToSave = $session->get(static::SAVE, []);
        if (!in_array($quizId, $quizzesToSave)) {
            $quizzesToSave[] = $quizId;
        }
        $session->set(static::SAVE, $quizzesToSave);
    }

    private function removeQuizToSave(int $quizId): void
    {
        $session = $this->requestStack->getSession();
        $quizzesToSave = $session->get(static::SAVE, []);
        if (in_array($quizId, $quizzesToSave)) {
            unset($quizzesToSave[array_search ($quizId, $quizzesToSave)]);
        }
        $session->set(static::SAVE, $quizzesToSave);
    }

    public function restart(int $quizId): void
    {
        $session = $this->requestStack->getSession();
        $data = $session->get(static::NAME, []);
        unset($data[$quizId]);
        $session->set(static::NAME, $data);
        $this->removeQuizToSave($quizId);
    }

}