<?php

namespace App\Controller\Api;

use App\Entity\Test;
use App\Repository\QuestionRepository;
use App\Repository\TestRepository;
use App\Service\NormalizerService;
use App\Service\QuestionHandler;
use App\Service\SessionService;
use App\Service\TestService;
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

    #[Route('/api/test/{id}', name: 'app_api_test_show', methods: ['GET'])]
    public function show(Test $test): JsonResponse
    {
        return $this->json(
            $test,
            200,
            ['charset=utf-8'],
            ['groups' => 'main_test'],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    #[Route('/api/test/{slug}/question/{count}', name: 'app_api_test_question', methods: ['GET'])]
    public function getRandomQuestion(Test               $test,
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
            $questions = $questionRepository->getRandomQByTest($test, $count);

//todo убрать костыль и сделать опцией типа по умолчанию перетасовывать варианты и подвопросы
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
    public function handle(Request           $request,
                           AppUpLoadedAsset  $upLoadedAsset,
                           NormalizerService $normalizerService,
                           TestService       $testService
    ): JsonResponse
    {

        $data = json_decode($request->getContent(), true);

        $response = $testService->handle($data);
        return $this->json($response['response'],
            $response['status'],
            ['charset=utf-8'],
            [
                'groups' => 'handle',
                AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
                AbstractNormalizer::CALLBACKS => [
                    'image' => $normalizerService->imageCallback($upLoadedAsset),
                ]
            ],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    #[Route('/api/auth/test/handle', name: 'app_api_auth_test_handle', methods: ['POST'])]
    public function handleByUser(Request           $request,
                                 AppUpLoadedAsset  $upLoadedAsset,
                                 NormalizerService $normalizerService,
                                 TestService       $testService
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $response = $testService->handle($data, $this->getUser());
        return $this->json($response['response'],
            $response['status'],
            ['charset=utf-8'],
            [
                'groups' => 'handle',
                AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
                AbstractNormalizer::CALLBACKS => [
                    'image' => $normalizerService->imageCallback($upLoadedAsset),
                ]
            ],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

}
