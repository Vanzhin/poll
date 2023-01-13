<?php

namespace App\Service;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Quiz;
use App\Entity\Result;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class QuizService
{
    const NAME = 'quiz';
    const SAVE = 'quiz_to_save';
    const CURRENT = 'current_quiz';
    private static int $currentQuestion;

    public function __construct(private readonly RequestStack           $requestStack,
                                private readonly EntityManagerInterface $entityManager)
    {
    }

    public function updateOrCreate(int $quizId): void
    {
        $session = $this->requestStack->getSession();
        $data = $session->get(static::NAME, []);

        if (isset($data[$quizId])) {
            self::$currentQuestion = count($data[$quizId]);
        } else {
            self::$currentQuestion = 0;
            $session->set(static::NAME, [
                $quizId => [
                ]
            ]);
        }
        $this->addCurrent($quizId);

    }

    private function addCurrent(int $quizId): void
    {
        $this->requestStack->getSession()->set(static::CURRENT, $quizId);
    }

    private function removeCurrent(int $quizId): void
    {
        $this->requestStack->getSession()->remove(static::CURRENT);
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
        $this->setCurrentQuestion($data[$quizId]);
        $session->set(static::NAME, $data);
    }

    public function getCurrentQuestion(): int
    {
        return self::$currentQuestion;
    }

    private function setCurrentQuestion(array $array): void
    {
        self::$currentQuestion = count($array);

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
        if ($answers) {
            foreach ($quiz->getQuestion() as $question) {
                $quiz->removeQuestion($question);
                $question->setCurrentAnswer($answers[$question->getId()]);
                $quiz->addQuestion($question);
            }
        }

        return $quiz;
    }

    public function addToSave(int $quizId): void
    {
        $session = $this->requestStack->getSession();
        $quizzesToSave = $session->get(static::SAVE, []);
        if (!in_array($quizId, $quizzesToSave)) {
            $quizzesToSave[] = $quizId;
        }
        $session->set(static::SAVE, $quizzesToSave);
    }

    private function removeToSave(int $quizId): void
    {
        $session = $this->requestStack->getSession();
        $quizzesToSave = $session->get(static::SAVE, []);
        if (in_array($quizId, $quizzesToSave)) {
            unset($quizzesToSave[array_search($quizId, $quizzesToSave)]);
        }
        $session->set(static::SAVE, $quizzesToSave);
    }

    public function restart(int $quizId): void
    {
        $session = $this->requestStack->getSession();
        $data = $session->get(static::NAME, []);
        unset($data[$quizId]);
        $session->set(static::NAME, $data);
        $this->removeToSave($quizId);
        $this->removeCurrent($quizId);

    }

    public function saveOneResult(User $user, int $quizId): bool
    {
        $session = $this->requestStack->getSession();
        $userAnswers = $session->get(static::NAME, [])[$quizId];
        if ($userAnswers) {
            $quiz = $this->entityManager->find(Quiz::class, $quizId);
            $result = new Result();
            $result
                ->setUser($user)
                ->setQuiz($quiz)
                ->setScore($this->getScore($quiz));
            $this->entityManager->persist($result);
            foreach ($userAnswers as $questionId => $userAnswer) {
                $answer = new Answer();
                $answer
                    ->setResult($result)
                    ->setQuestion($this->entityManager->find(Question::class, $questionId))
                    ->setContent($userAnswer);
                $this->entityManager->persist($answer);
            }
            $this->entityManager->flush();
            $this->restart($quiz->getId());
            return true;

        } else {
            return false;
        }

    }

    public function saveAllResults(User $user): bool
    {
        $session = $this->requestStack->getSession();
        $quizzesToSave = $session->get(static::SAVE, []);
        if (!empty($quizzesToSave)) {
            foreach ($quizzesToSave as $quizId) {
                $quiz = $this->entityManager->find(Quiz::class, $quizId);
                $this->saveOneResult($user, $quiz);
            }
            return true;
        } else {
            return false;
        }
    }

}