<?php

namespace App\Controller\Api;

use App\Entity\Test;
use App\Repository\QuestionRepository;
use App\Repository\TestRepository;
use App\Service\NormalizerService;
use App\Service\QuestionHandler;
use App\Service\SessionService;
use App\Twig\Extension\AppUpLoadedAsset;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

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
    public function getRandomQuestion(
        Test               $test,
        QuestionRepository $questionRepository,
        QuestionHandler    $questionService,
        SessionService     $sessionService,
        int                $count,
        AppUpLoadedAsset   $upLoadedAsset,
        NormalizerService  $normalizerService
    ): JsonResponse
    {
        try {
            $sessionService->remove(QuestionHandler::SHUFFLED);
//            $questions = $questionService->getPreparedQuestions($questionRepository->getRandomQByTest($test, $count));
            $questions = $questionRepository->getRandomQByTest($test, $count);
//todo убрать костыль
            foreach ($questions as $question) {
                $variants = $question->getVariant()->toArray();
                shuffle($variants);
                $subtitles = $question->getSubTitle();
                shuffle($subtitles);
                $question->setSubtitle($subtitles);
                $question->getVariant()->clear();

                foreach ($variants as $variant) {
                    $question->addVariant($variant);
                }
            }
            $response = [
                'test' => $test->getTitle(),
                'questions' => $questions
            ];

            $sessionService->add($questionService->prepareForSession($questions), QuestionHandler::SHUFFLED);

            $status = 200;
        } catch (\Exception $e) {
            $response = ['error' => $e->getMessage()];
            $status = 422;
        } finally {
            return $this->json($response,
                $status,
                ['charset=utf-8'],
                [
                    'groups' => 'test',
                    AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
                    AbstractNormalizer::CALLBACKS => [
                        'image' => $normalizerService->imageCallback($upLoadedAsset),
                    ]
                ],

            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }
    }

    #[Route('/api/test/handle', name: 'app_api_test_handle', methods: ['POST'])]
    public function handle(Request $request, QuestionHandler $questionService): JsonResponse
    {

        $data = json_decode($request->getContent(), true);
        try {
            $response = $questionService->handle($data);
            $status = 200;

        } catch (\Exception $e) {
            $response = ["error" => $e->getTrace()];
            $status = 422;

        } finally {
            return $this->json($response,
                $status,
                ['charset=utf-8'],
                ['groups' => 'main'],
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }
    }

    #[Route('/api/auth/test/handle', name: 'app_api_auth_test_handle', methods: ['POST'])]
    public function handleByUser(Request $request, QuestionHandler $questionService, SessionService $sessionService): JsonResponse
    {
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);
        try {
            $response = $questionService->handle($data, $user);
            $status = 200;

        } catch (\Exception $e) {
            $response = ["error" => $e->getTrace()];
            $status = 422;

        } finally {
            $sessionService->remove(QuestionHandler::SHUFFLED);
            return $this->json($response,
                $status,
                ['charset=utf-8'],
                ['groups' => 'main'],
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }
    }
}
