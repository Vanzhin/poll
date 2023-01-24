<?php

namespace App\Controller\Api;

use App\Entity\Article;
use App\Entity\Test;
use App\Repository\ArticleRepository;
use App\Repository\TestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/api/test', name: 'app_api_test', methods: ['GET'])]
    public function index(TestRepository $repository): JsonResponse
    {
        $tests = $repository->findAll();

        return $this->json(
            $tests,
            200,
            ['charset=utf-8'],
            ['groups' => 'main'],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    #[Route('/api/test/{slug}', name: 'app_api_test_show', methods: ['GET'])]
    public function show(Test $test): JsonResponse
    {

        return $this->json(
            $test,
            200,
            ['charset=utf-8'],
            ['groups' => 'main'],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    #[Route('/api/test/{slug}/question/{count}', name: 'app_api_test_show', methods: ['GET'])]
    public function getRandomQuestion(Test $test, int $count): JsonResponse
    {
//        $t = $testRepository->findAllQuestions($test->getId());
//todo убрать это
        $questions = [];
        foreach ($test->getTicket() as $ticket) {
            foreach ($ticket->getQuestion() as $question) {
                $questions[] = $question;
            }

        }
        shuffle($questions);
        $data = array_chunk($questions, $count);
        return $this->json(['test' => $test->getTitle(), 'questions' => $data[0]],
            200,
            ['charset=utf-8'],
            ['groups' => 'main'],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
