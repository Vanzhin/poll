<?php

namespace App\Controller;

use App\Entity\Quiz;
use App\Repository\QuizRepository;
use App\Service\Paginator;
use App\Service\QuizService;
use SessionHandlerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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

            $quizService->answer($quiz->getId());
            return $this->redirectToRoute('app_home_test', ['quiz' => $quiz->getId()]);
        }

        $question = $quiz->getQuestion()->offsetGet($quizService->getCurrent());
        if ($question) {
            return $this->render('quiz/show.html.twig', [
                'quiz' => $quiz,
                'question' => $question,
            ]);
        } else {
            return $this->redirectToRoute('app_quiz_final', ['quiz' => $quiz->getId()]);

        }

    }

    #[Route('/quiz/{quiz}/final', name: 'app_quiz_final')]
    public function final(Quiz $quiz, Request $request): Response
    {
        $session = $request->getSession();
        $answers = $session->get('quiz')[$quiz->getId()];
        unset($answers['current'], $answers['toSave']);
        foreach ($answers as $key => $answer) {
            $answers[$key] = array_values($answer);

        }

        //результат
        $score = 0;
        foreach ($quiz->getQuestion() as $question) {
            if ($question->getAnswer() === $answers[$question->getId()]) {
                $score++;
            }
        }

        foreach ($quiz->getQuestion() as $question) {
            $quiz->removeQuestion($question);
            $question->setCurrentAnswer($answers[$question->getId()]);
            $quiz->addQuestion($question);

        }
        $newQData = [];
        foreach ($session->get('quiz') as $key => $value) {
            if ($key === $quiz->getId()) {
                $value['toSave'] = 1;
            }
            $newQData[$key] = $value;
        }

        $session->set('quiz', $newQData);

        return $this->render('quiz/final.html.twig',
            [
                'score' => $score,
                'quiz' => $quiz,
            ]

        );
    }

    #[Route('/quiz/{quiz}/restart', name: 'app_quiz_restart')]
    public function restart(Quiz $quiz, Request $request): Response
    {
        $session = $request->getSession();
        $data = $session->get('quiz');
        unset($data[$quiz->getId()]);
        $session->set('quiz', $data);
        return $this->redirectToRoute('app_quiz_show', ['quiz' => $quiz->getId()]);

    }
}

