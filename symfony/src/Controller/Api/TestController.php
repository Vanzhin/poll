<?php

namespace App\Controller\Api;

use App\Entity\Test;
use App\Repository\TestRepository;
use App\Service\QuestionService;
use App\Service\SessionService;
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

    #[Route('/api/test/{slug}/question/{count}', name: 'app_api_test_question', methods: ['GET'])]
    public function getRandomQuestion(Test $test, TestRepository $testRepository, QuestionService $questionService, SessionService $sessionService, int $count): JsonResponse
    {
        try {
            $sessionService->remove($questionService::SHUFFLED);
            $response = ['test' => $test->getTitle(), 'questions' => $questionService->shuffleVariants($testRepository->getRandomQuestions($test, $count))];
            $status = 200;
        } catch (Exception $e) {
            $response = ['error' => $e->getMessage()];
            $status = 422;
        } finally {
            return $this->json($response,
                $status,
                ['charset=utf-8'],
                ['groups' => 'main'],
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }
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

    #[Route('/api/auth/test/handle', name: 'app_api_auth_test_handle', methods: ['POST'])]
    public function handleByUser(Request $request, QuestionService $questionService): JsonResponse
    {
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);
        $response = [];
        foreach ($data as $answerData) {
            $response[] = $questionService->handle($answerData, $user);
        }

        return $this->json(['questions' => $response],
            200,
            ['charset=utf-8'],
            ['groups' => 'main'],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
