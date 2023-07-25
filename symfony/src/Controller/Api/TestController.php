<?php

namespace App\Controller\Api;

use App\Action\Test\GetTestListAction;
use App\Entity\Question;
use App\Entity\Test;
use App\Entity\Ticket;
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

    #[Route('/api/test/{id}/question/{count}', name: 'app_api_test_question', methods: ['GET'])]
    public function getRandomQuestion(Test               $test,
                                      QuestionRepository $questionRepository,
                                      QuestionHandler    $questionService,
                                      SessionService     $sessionService,
                                      int                $count,
                                      AppUpLoadedAsset   $upLoadedAsset,
                                      NormalizerService  $normalizerService,
                                      TestService        $testService
    ): JsonResponse
    {
        try {
            $sessionService->remove(QuestionHandler::SHUFFLED);

            //todo убрать костыль и сделать опцией типа по умолчанию перетасовывать варианты и подвопросы
            $sections = [];

            $questions = $questionRepository->getAllPublishedByTest($test);
            foreach ($questions as $question) {

                /** @var Question $question */
                $sections[$question->getSection()->getId()][] = $question->getId();
            };
            $questionsId = [];
            $i = 0;
            while ($i < $count) {
                if (count($sections) > 0) {
                    foreach ($sections as $key => $section) {
                        if (count($section) > 0) {
                            $randomId = array_rand($section);
                            $questionsId[] = $section[$randomId];
                            unset($sections[$key][$randomId]);

                        } else {
                            unset($sections[$key]);
                            break;
                        }
                        $i++;
                    }
                } else {
                    break;
                }

            }
            $questions = $testService->getQuestionForResponse($questionRepository->getByIdsSortBySection($questionsId));
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

    #[Route('/api/ticket/{id}/question', name: 'app_api_test_question_ticket', methods: ['GET'])]
    public function getTicketQuestion(Ticket            $ticket,
                                      QuestionHandler   $questionService,
                                      SessionService    $sessionService,
                                      AppUpLoadedAsset  $upLoadedAsset,
                                      NormalizerService $normalizerService,
                                      TestService       $testService
    ): JsonResponse
    {
        try {
            $sessionService->remove(QuestionHandler::SHUFFLED);
            //todo убрать костыль и сделать опцией типа по умолчанию перетасовывать варианты и подвопросы

            $questions = $testService->getQuestionForResponse($ticket->getQuestion()->toArray());

            $response = [
                'ticket'=>$ticket,
                'test' => $ticket->getTest()->getTitle(),
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

    #[Route('/api/search/test', name: 'app_api_test_search', methods: ['GET','POST'])]
    public function search(Request $request, GetTestListAction $action): JsonResponse
    {
        return $action($request);
    }

}
