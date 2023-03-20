<?php

namespace App\Controller\Api\Admin;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use App\Service\NormalizerService;
use App\Service\Paginator;
use App\Service\QuestionService;
use App\Service\ValidationService;
use App\Service\VariantService;
use App\Twig\Extension\AppUpLoadedAsset;
use Doctrine\ORM\EntityManagerInterface;
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
    #[Route('/api/admin/question', name: 'app_api_admin_question_index', methods: ['GET'])]
    public function index(Paginator $paginator, QuestionRepository $repository, AppUpLoadedAsset $upLoadedAsset, NormalizerService $normalizerService): JsonResponse
    {
        $pagination = $paginator->getPagination($repository->findLastUpdatedQuery());
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

    #[Route('/api/admin/question/{id}', name: 'app_api_admin_question_show', methods: ['GET'])]
    public function show(Question $question, AppUpLoadedAsset $upLoadedAsset, NormalizerService $normalizerService): JsonResponse
    {
        return $this->json(
            $question,
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

    #[Route('/api/admin/question/create', name: 'app_api_admin_question_create', methods: 'POST')]
    public function create(Request $request, QuestionService $questionService, ValidationService $validation): JsonResponse
    {
        $data = $request->request->all();
        $image = $request->files->get('questionImage');
        $question = $questionService->make(new Question(), $data['question'] ?? []);
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
    public function edit(Question $question, Request $request, QuestionService $questionService, ValidationService $validation): JsonResponse
    {
        $data = $request->request->all();
        $image = $request->files->get('questionImage',false);
        $question = $questionService->make($question, $data['question'] ?? []);
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
    public function createWithVariant(Request $request, QuestionService $questionService, ValidationService $validation, VariantService $variantService, EntityManagerInterface $em,): JsonResponse
    {
        $data = $request->request->all();
        $questionImage = $request->files->get('questionImage', false);
        $variantImages = $request->files->get('variantImage', []);
        $response = $questionService->saveWithVariantIfValid(new Question(), $data, $questionImage, $variantImages);
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
    public function editWithVariant(Question $question, Request $request, QuestionService $questionService, ValidationService $validation, VariantService $variantService, EntityManagerInterface $em,): JsonResponse
    {
//        $question->getVariant()->clear();
        $data = $request->request->all();
        $questionImage = $request->files->get('questionImage', false);
        $variantImages = $request->files->get('variantImage', []);

        $response = $questionService->saveWithVariantIfValid($question, $data, $questionImage, $variantImages);
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
}
