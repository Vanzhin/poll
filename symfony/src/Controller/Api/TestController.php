<?php

namespace App\Controller\Api;

use App\Entity\Test;
use App\Repository\TestRepository;
use App\Service\QuestionService;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
    public function getRandomQuestion(Test $test, TestRepository $testRepository, int $count): JsonResponse
    {
        try {
            $response = ['test' => $test->getTitle(), 'questions' => $testRepository->getRandomQuestions($test, $count)];
        } catch (Exception $e) {

            $response = $e->getMessage();
        }

        return $this->json($response,
            200,
            ['charset=utf-8'],
            ['groups' => 'main'],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    #[Route('/api/test/handle', name: 'app_api_test_handle', methods: ['POST'])]
    public function handle(Request $request, QuestionService $questionService): JsonResponse
    {

        $data = json_decode($request->getContent(), true);
        $response = [];
        foreach ($data as $answerData) {
            $response[] = $questionService->handle($answerData);
        }

        return $this->json(['questions' => $response],
            200,
            ['charset=utf-8'],
            ['groups' => 'main'],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

}
