<?php

namespace App\Controller\Api;

use App\Entity\Question;
use App\Entity\Test;
use App\Repository\TestRepository;
use App\Service\QuestionService;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
    public function handle(Request $request, QuestionService $questionService)
    {

        $data = json_decode($request->getContent(), true);
        $response = [];
        foreach ($data as $answerData){

            $response[] = $questionService->handle($answerData);
        }
//        $question = $em->find(Question::class,$test[0]);
//        return new Response($data);

//        return $data;
//        $test = [];
//        foreach ($data as $id => $value){
//            $test[$id] = $value;
//        }
        return $this->json($response,
            200,
            ['charset=utf-8'],
            ['groups' => 'main'],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

}
