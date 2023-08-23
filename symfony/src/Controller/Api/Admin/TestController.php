<?php

namespace App\Controller\Api\Admin;

use App\Action\Test\GetMinTrudTest;
use App\Action\Test\UploadQuestionAction;
use App\Entity\Test;
use App\Factory\Test\TestFactory;
use App\Repository\Question\QuestionRepository;
use App\Repository\SectionRepository;
use App\Repository\Test\TestRepository;
use App\Repository\TicketRepository;
use App\Service\NormalizerService;
use App\Service\Paginator;
use App\Service\ValidationService;
use App\Twig\Extension\AppUpLoadedAsset;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

class TestController extends AbstractController
{
    #[Route('/api/admin/test', name: 'app_api_admin_test')]
    public function index(Paginator $paginator, TestRepository $repository): JsonResponse
    {
        $pagination = $paginator->getPagination($repository->findLastUpdatedQuery());
        if ($pagination->count() > 0) {
            $response['test'] = $pagination;

        }
        $response['pagination'] = $paginator->getInfo($pagination);
        return $this->json(
            $response,
            200,
            ['charset=utf-8'],
            [
                'groups' => 'admin_test_general',
                AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
            ],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    #[Route('/api/admin/test/mintrud', name: 'app_api_admin_test_mintrud')]
    public function getMinTrudTest(GetMinTrudTest $minTrudTest): JsonResponse
    {
        return $minTrudTest->getAll();
    }

    #[Route('/api/admin/test/{id}', name: 'app_api_admin_test_show', methods: 'GET')]
    public function show(Test $test): JsonResponse
    {
        return $this->json(
            $test,
            200,
            ['charset=utf-8'],
            [
                'groups' => 'admin_test_general',
                AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
            ],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    #[Route('/api/admin/test/create', name: 'app_api_admin_test_create', methods: 'POST')]
    public function create(Request $request, ValidationService $validation, EntityManagerInterface $em, TestFactory $testFactory): JsonResponse
    {
        $data = $request->request->all();

        $test = $testFactory->createBuilder()->updateOrCreate($data);
        if (count($validation->validate($test)) > 0) {
            return $this->json(
                [
                    'message' => 'Ошибка при вводе данных',
                    'error' => $validation->validate($test)
                ],
                422,
                ['charset=utf-8'],
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }

        try {
            $em->persist($test);
            $em->flush();
            $response = [
                'message' => 'Тест создан',
                'testId' => $test->getId()
            ];
            $status = 200;
        } catch (\Exception $e) {
            $response = ['error' => $e->getMessage()];
            $status = 501;
        } finally {
            return $this->json($response,
                $status,
                ['charset=utf-8'],
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }
    }

    #[Route('/api/admin/test/{id}/edit', name: 'app_api_admin_test_edit', methods: 'POST')]
    public function edit(Test $test, Request $request, ValidationService $validation, EntityManagerInterface $em, TestFactory $testFactory): JsonResponse
    {
        $data = $request->request->all();

        $test = $testFactory->createBuilder()->updateOrCreate($data, $test);

        if (count($validation->validate($test)) > 0) {
            return $this->json([
                'message' => 'Ошибка при вводе данных',
                'error' => $validation->validate($test)],
                422,
                ['charset=utf-8'],
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }

        try {
            $em->persist($test);
            $em->flush();
            $response = [
                'message' => 'Тест обновлен',
                'testId' => $test->getId()
            ];
            $status = 200;
        } catch (\Exception $e) {
            $response = ['error' => $e->getMessage()];
            $status = 501;
        } finally {
            return $this->json($response,
                $status,
                ['charset=utf-8'],
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }
    }

    #[Route('/api/admin/test/{id}/delete', name: 'app_api_admin_test_delete')]
    public function delete(Test $test, EntityManagerInterface $em): JsonResponse
    {
        try {
            $em->remove($test);
            $em->flush();

            $response = [
                'message' => 'Тест удален',
            ];
            $status = 200;
        } catch (\Exception $e) {
            $response = ['error' => $e->getMessage()];
            $status = 501;
        } finally {
            return $this->json($response,
                $status,
                ['charset=utf-8'],
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }
    }

    #[Route('/api/admin/test/{id}/upload', name: 'app_api_admin_test_upload', methods: 'POST')]
    public function upload(Request              $request,
                           UploadQuestionAction $uploadQuestionAction): JsonResponse
    {
        return $uploadQuestionAction->upload($request);

    }

    #[Route('/api/admin/test/{id}/question', name: 'app_api_admin_test_question', methods: 'GET')]
    public function getQuestion(Test $test, Paginator $paginator, QuestionRepository $repository, AppUpLoadedAsset $upLoadedAsset, NormalizerService $normalizerService): JsonResponse
    {
        $pagination = $paginator->getPagination($repository->findLastUnPublishedByTestQuery($test));
        if ($pagination->count() > 0) {
            $response['question'] = $pagination;

        }
        $response['pagination'] = $paginator->getInfo($pagination);
        return $this->json(
            $response,
            200,
            ['charset=utf-8'],
            [
                'groups' => ['admin_question'],
                AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
                AbstractNormalizer::CALLBACKS => [
                    'image' => $normalizerService->imageCallback($upLoadedAsset),
                ]
            ],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    #[Route('/api/admin/test/{id}/ticket', name: 'app_api_admin_test_ticket', methods: 'GET')]
    public function getTicket(Test $test, Paginator $paginator, TicketRepository $repository): JsonResponse
    {
        $pagination = $paginator->getPagination($repository->findLastUpdatedByTestQuery($test));
        if ($pagination->count() > 0) {
            $response['ticket'] = $pagination;

        }
        $response['pagination'] = $paginator->getInfo($pagination);
        return $this->json(
            $response,
            200,
            ['charset=utf-8'],
            [
                'groups' => 'admin_ticket',
                AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
            ],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    #[Route('/api/admin/test/{id}/section', name: 'app_api_admin_test_section', methods: 'GET')]
    public function getSection(Test $test, Paginator $paginator, SectionRepository $repository): JsonResponse
    {
        $pagination = $paginator->getPagination($repository->findLastUpdatedByTestQuery($test));
        if ($pagination->count() > 0) {
            $response['section'] = $pagination;

        }
        $response['pagination'] = $paginator->getInfo($pagination);
        return $this->json(
            $response,
            200,
            ['charset=utf-8'],
            [
                'groups' => 'admin_section',
                AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
            ],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}