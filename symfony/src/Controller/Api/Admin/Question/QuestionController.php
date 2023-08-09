<?php

namespace App\Controller\Api\Admin\Question;

use App\Action\Question\GetQuestion;
use App\Controller\Api\Admin\Question\Action\ListAction;
use App\Entity\Question;
use App\Entity\Section;
use App\Entity\Test;
use App\Factory\Question\QuestionFactory;
use App\Repository\Question\QuestionRepository;
use App\Service\NormalizerService;
use App\Service\Paginator;
use App\Service\QuestionService;
use App\Service\ValidationService;
use App\Twig\Extension\AppUpLoadedAsset;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

class QuestionController extends AbstractController
{
    public function __construct(
        private readonly ListAction $listAction
    )
    {
    }

    #[Route('/api/admin/question', name: 'app_api_admin_question_index', methods: ['GET'])]
    public function index(GetQuestion $getQuestion): JsonResponse
    {
        return $getQuestion->getAll();
    }

    #[Route('/api/admin/question/{id}', name: 'app_api_admin_question_show', methods: ['GET'])]
    public function show(Request $request, GetQuestion $getQuestion): JsonResponse
    {
        return $getQuestion->get($request);
    }

    #[Route('/api/admin/question/create', name: 'app_api_admin_question_create', methods: 'POST')]
    public function create(Request $request, QuestionService $questionService, ValidationService $validation, QuestionFactory $factory): JsonResponse
    {
        $data = $request->request->all();
        $image = $request->files->get('questionImage');

        $question = $factory->createBuilder()->buildQuestion($data['question'], $this->getUser());
        $errors = $validation->entityWithImageValidate($question, $image);
        if (!is_null($errors) && count($errors) > 0) {
            return $this->json([
                'message' => 'Ошибка при вводе данных',
                'error' => $errors],
                422,
                ['charset=utf-8'],
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }

        $response = $questionService->saveResponse($question, $image);
        return $this->json($response['response'],
            $response['status'],
            ['charset=utf-8'],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    #[Route('/api/admin/question/{id}/edit', name: 'app_api_admin_question_edit', methods: 'POST')]
    public function edit(Question $question, Request $request, QuestionService $questionService, ValidationService $validation, QuestionFactory $factory): JsonResponse
    {
        $data = $request->request->all();
        $image = $request->files->get('questionImage', false);

        $question = $factory->createBuilder()->buildQuestion($data['question'], $this->getUser(), $question);
        $errors = $validation->entityWithImageValidate($question, $image instanceof UploadedFile ? $image : null);
        if (!is_null($errors) && count($errors) > 0) {
            return $this->json([
                'message' => 'Ошибка при вводе данных',
                'error' => $errors],
                422,
                ['charset=utf-8'],
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }
        $response = $questionService->saveResponse($question, $image);
        return $this->json($response['response'],
            $response['status'],
            ['charset=utf-8'],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);

    }

    #[Route("api/admin/question/{id}/delete", name: 'app_api_admin_question_delete')]
    public function delete(Question $question, QuestionService $questionService): Response
    {
        try {
            $questionService->delete($question);
            $response = [
                'message' => 'Вопрос удален',
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

    #[Route('/api/admin/question/create_with_variant', name: 'app_api_admin_question_create_with_variant', methods: 'POST')]
    public function createWithVariant(Request $request, QuestionService $questionService, QuestionFactory $questionFactory): JsonResponse
    {
        $data = $request->request->all();
        $questionImage = $request->files->get('questionImage', false);
        $variantImages = $request->files->get('variantImage', []);
        $subtitleImages = $request->files->get('subTitleImage', []);
        $question = $questionFactory->createBuilder()->buildQuestion($data['question'] ?? [], $this->getUser());
        $response = $questionService->saveWithVariantIfValid($question, $data, $questionImage, $variantImages, $subtitleImages);
        if (key_exists('error', $response)) {
            $status = 422;
        } else {
            $status = 200;
        }
        return $this->json($response,
            $status,
            ['charset=utf-8'],
            ['groups' => 'create',]
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    #[Route('/api/admin/question/{id}/edit_with_variant', name: 'app_api_admin_question_edit_with_variant', methods: 'POST')]
    public function editWithVariant(Question $question, Request $request, QuestionService $questionService, QuestionFactory $questionFactory): JsonResponse
    {
        $data = $request->request->all();
        $questionImage = $request->files->get('questionImage', false);
        $variantImages = $request->files->get('variantImage', []);
        $subtitleImages = $request->files->get('subTitleImage', []);

        $question = $questionFactory->createBuilder()->buildQuestion($data['question'] ?? [], $this->getUser(), $question);
        $response = $questionService->saveWithVariantIfValid($question, $data, $questionImage, $variantImages, $subtitleImages);
        if (key_exists('error', $response)) {
            $status = 422;
        } else {
            $status = 200;
        }
        return $this->json($response,
            $status,
            ['charset=utf-8'],
            ['groups' => 'create',]
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    #[Route('/api/admin/question/publish', name: 'app_api_admin_question_publish', methods: 'POST')]
    public function publish(Request $request, QuestionService $questionService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $questionIds = key_exists('questionIds', $data) ? $data['questionIds'] : [];
        try {
            $changed = $questionService->switchPublishForAll($questionIds, $this->getUser());

            $publishedMessage = key_exists('published', $changed) ? sprintf('Опубликовано %d вопросов(а).', count($changed['published'])) : null;
            $unPublishedMessage = key_exists('unpublished', $changed) ? sprintf('Снято с публикации %d вопросов(а).', count($changed['unpublished'])) : null;
            $message = ($publishedMessage ?? '') . ($unPublishedMessage ?? '');

            $response = [
                'message' => $message
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

    #[Route('/api/admin/question/publish/test/{id}', name: 'app_api_admin_question_publish_test', methods: 'GET')]
    public function publishAllByTest(Test $test, Request $request, QuestionService $questionService, QuestionRepository $questionRepository): JsonResponse
    {
        $publish = filter_var($request->query->get('publish'), FILTER_VALIDATE_BOOLEAN);
        $questions = $questionRepository->findAllByPublishedByTest($test, $publish);
        try {
            $questionService->changePublished($questions, $this->getUser());
            $response = [
                'message' => sprintf('%s %d вопросов(а).', $publish ? 'Опубликовано' : 'Снято с публикации', count($questions))
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

    #[Route('/api/admin/question/section/{id}', name: 'app_api_admin_question_section', methods: ['GET'])]
    public function showAllBySection(Section $section, Paginator $paginator, QuestionRepository $repository, AppUpLoadedAsset $upLoadedAsset, NormalizerService $normalizerService): JsonResponse
    {
        $pagination = $paginator->getPagination($repository->findLastUpdatedBySectionQuery($section));
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
                AbstractNormalizer::CALLBACKS => [
                    'image' => $normalizerService->imageCallback($upLoadedAsset),
                ]
            ],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    #[Route('/api/admin/question/list', name: 'app_api_admin_question_list', methods: ['POST'])]
    public function list(Request $request): JsonResponse
    {
        $data = array_merge($request->query->all() ?? [], json_decode($request->getContent(), true) ?? []);
        return $this->listAction->run($data);
    }
}