<?php

namespace App\Controller\Api\Admin;

use App\Entity\Question;
use App\Entity\Test;
use App\Repository\QuestionRepository;
use App\Repository\SectionRepository;
use App\Repository\TestRepository;
use App\Repository\TicketRepository;
use App\Service\FileHandler;
use App\Service\Paginator;
use App\Service\QuestionService;
use App\Service\SectionService;
use App\Service\TestService;
use App\Service\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

class TestController extends AbstractController
{
    #[Route('/api/admin/test', name: 'app_api_admin_test')]
    public function index(Paginator $paginator, TestRepository $repository): JsonResponse
    {
        $pagination = $paginator->getPagination($repository->findLastUpdatedQuery(), 10);
        if ($pagination->count() > 0) {
            $response['test'] = $pagination;

        }
        $response['pagination'] = $paginator->getInfo($pagination);
        return $this->json(
            $response,
            200,
            ['charset=utf-8'],
            [
                'groups' => 'admin',
                AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
            ],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    #[Route('/api/admin/test/{id}', name: 'app_api_admin_test_show', methods: 'GET')]
    public function show(Test $test): JsonResponse
    {
        return $this->json(
            $test,
            200,
            ['charset=utf-8'],
            [
                'groups' => 'admin',
                AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
            ],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    #[Route('/api/admin/test/create', name: 'app_api_admin_test_create', methods: 'POST')]
    public function create(Request $request, ValidationService $validation, EntityManagerInterface $em, TestService $testService): JsonResponse
    {
        $data = $request->request->all();

        $test = $testService->save(new Test(), $data);
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
    public function edit(Test $test, Request $request, ValidationService $validation, EntityManagerInterface $em, TestService $testService): JsonResponse
    {
        $data = $request->request->all();

        $test = $testService->save($test, $data);

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
    public function upload(Test              $test,
                           Request           $request,
                           ValidationService $validation,
                           FileHandler       $handler,
                           QuestionService   $questionService,
                           SectionService    $sectionService
    ): JsonResponse
    {

        $file = $request->files->get('file');
        $errors = $validation->fileValidate($file);
        if (!is_null($errors) && count($errors) > 0) {
            return $this->json([
                'message' => 'Ошибка при вводе данных',
                'error' => $errors],
                422,
                ['charset=utf-8'],
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }
        try {
            $questionData = $handler->getQuestion($file);
            $status = 200;
            $total = [];
            foreach ($questionData as $key => $data) {
                $data['test'] = $test->getId();
                $question = $questionService->make(new Question(), $data);
                if ($validation->validate($question)) {
                    $total[$key]['error']['question'] = $validation->validate($question);
                }
                if ($validation->manyVariantsValidate($data)) {
                    $total[$key]['error']['variant'] = $validation->manyVariantsValidate($data);
                    $total[$key]['error']['variant']['question'] = $data;
                }

            }
            if ($total) {
                $response = $total;
                $status = 422;
            } else {
                $questions = [];
                foreach ($questionData as $data) {
                    $data['test'] = $test->getId();
                    if ($data['section']) {
                        $data['section'] = $sectionService->createIfNotExist($data['section'], $test)->getId();
                    }
                    $questions[] = $questionService->saveWithVariant(new Question(), $data, $data['variant']);
                };
                $response = $questionService->getUploadedQuestionsSummary($questions);
            }

        } catch (\Exception $e) {
            $response = ['error' => $e->getMessage()];
            $status = 501;
        } catch (FilesystemException $e) {
            $response = ['error' => $e->getMessage()];
            $status = 501;
        } finally {
            return $this->json($response,
                $status,
                ['charset=utf-8'],
                ['groups' => 'create']
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }

    }

    #[Route('/api/admin/test/{id}/question', name: 'app_api_admin_test_question', methods: 'GET')]
    public function getQuestion(Test $test, Paginator $paginator, QuestionRepository $repository): JsonResponse
    {
        $pagination = $paginator->getPagination($repository->findLastUpdatedByTestQuery($test), 5);
        if ($pagination->count() > 0) {
            $response['question'] = $pagination;

        }
        $response['pagination'] = $paginator->getInfo($pagination);
        return $this->json(
            $response,
            200,
            ['charset=utf-8'],
            [
                'groups' => 'admin_question',
                AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
            ],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    #[Route('/api/admin/test/{id}/ticket', name: 'app_api_admin_test_ticket', methods: 'GET')]
    public function getTicket(Test $test, Paginator $paginator, TicketRepository $repository): JsonResponse
    {
        $pagination = $paginator->getPagination($repository->findLastUpdatedByTestQuery($test), 5);
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
        $pagination = $paginator->getPagination($repository->findLastUpdatedByTestQuery($test), 5);
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
