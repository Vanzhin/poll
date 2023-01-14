<?php

namespace App\Controller;

use App\Entity\Quiz;
use App\Repository\QuizRepository;
use App\Service\Paginator;
use App\Service\QuizService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends AbstractController
{

    #[Route('/quiz', name: 'app_quiz')]
    public function index(QuizRepository $quizRepository, Paginator $paginator): Response
    {
        return $this->render('quiz/index.html.twig',
            [
                'pagination' => $paginator->getPagination($quizRepository->findLastUpdatedQuery(), 10)
            ]

        );
    }

    #[Route('/quiz/{quiz}', name: 'app_quiz_show')]
    public function show(Quiz $quiz, QuizService $quizService, Request $request): Response
    {
        $quizService->updateOrCreate($quiz->getId());

        if ($request->isMethod('POST')) {

            $quizService->answerHandle($quiz->getId());
            return $this->redirectToRoute('app_home_test', ['quiz' => $quiz->getId()]);
        }

        $question = $quiz->getQuestion()->offsetGet($quizService->getCurrentQuestion());
        if ($question) {
            return $this->render('quiz/show.html.twig', [
                'quiz' => $quiz,
                'question' => $question,
                'currentQuestion' => $quizService->getCurrentQuestion(),
            ]);
        } else {
            return $this->redirectToRoute('app_quiz_final', ['quiz' => $quiz->getId()]);

        }

    }

    #[Route('/quiz/{quiz}/final', name: 'app_quiz_final')]
    public function final(Quiz $quiz, QuizService $quizService, Request $request): Response
    {
        $score = $quizService->getScore($quiz);

        $quizService->addToSave($quiz->getId());

        $quiz = $quizService->setUserAnswer($quiz);

        return $this->render('quiz/final.html.twig',
            [
                'score' => $score,
                'quiz' => $quiz,
            ]

        );
    }

    #[Route('/quiz/{quiz}/restart', name: 'app_quiz_restart')]
    public function restart(Quiz $quiz, QuizService $quizService): Response
    {
        $quizService->restart($quiz->getId());
        return $this->redirectToRoute('app_quiz_show', ['quiz' => $quiz->getId()]);

    }
}

