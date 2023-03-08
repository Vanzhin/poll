<?php

namespace App\Controller\Api;

use App\Entity\Question;
use App\Entity\Variant;
use App\Service\NormalizerService;
use App\Service\QuestionService;
use App\Service\ValidationService;
use App\Service\VariantService;
use App\Twig\Extension\AppUpLoadedAsset;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

class QuestionController extends AbstractController
{
    #[Route('/api/question', name: 'app_api_question')]
    public function index(): Response
    {
        return $this->render('api/question/index.html.twig', [
            'controller_name' => 'QuestionController',
        ]);
    }

    #[Route('/api/question/{id}', name: 'app_api_question_show', methods: ['GET'])]
    public function show(Question $question, AppUpLoadedAsset $upLoadedAsset, NormalizerService $normalizerService): JsonResponse
    {
        return $this->json(
            $question,
            200,
            ['charset=utf-8'],
            [
                'groups' => 'admin',
                AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
                AbstractNormalizer::CALLBACKS => [
                    'image' => $normalizerService->imageCallback($upLoadedAsset),
                ]
            ],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    #[Route('/api/question/create', name: 'app_api_question_create', methods: 'POST')]
    public function create(Request $request, QuestionService $questionService, ValidationService $validation): JsonResponse
    {
        $data = $request->request->all();
        $image = $request->files->get('questionImage');
        $question = $questionService->save(new Question(), $data['question'] ?? []);
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

    #[Route('/api/question/{id}/edit', name: 'app_api_question_edit', methods: 'POST')]
    public function edit(Question $question, Request $request, QuestionService $questionService, ValidationService $validation): JsonResponse
    {
        $data = $request->request->all();
        $image = $request->files->get('questionImage');
        $question = $questionService->save($question, $data['question'] ?? []);
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

    #[Route("api/question/{id}/delete", name: 'app_api_question_delete')]
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

    #[Route('/api/question/create_with_variant', name: 'app_api_question_create_with_variant', methods: 'POST')]
    public function createWithVariant(Request $request, QuestionService $questionService, ValidationService $validation, VariantService $variantService, EntityManagerInterface $em): JsonResponse
    {
        $data = $request->request->all();
        $questionImage = $request->files->get('questionImage');
        $variantImages = $request->files->get('variantImage');

        $question = $questionService->save(new Question(), $data['question'] ?? []);
        $questionErrors = $validation->entityWithImageValidate($question, $questionImage);
        $errors = $questionErrors;
        $em->beginTransaction();
        $em->persist($question);
        $em->flush();
        foreach ($data['variant'] as $key => $variantData){
            $variantData['questionId'] = 
            $variant = $variantService->save(new Variant(), $variantData);
            $image = $variantImages[$key];
            $errors[] = $validation->entityWithImageValidate($variant, $image);

        }
        try {
            $em->commit();
        } catch (\Exception $e) {
            $em->rollback();
        }
        $variantImages = $request->files->get('variantImage');
        $variantErrors = $validation->manyVariantsValidate($data ?? [], $variantImages) ?? [];
        $errors = array_merge($errors, $variantErrors);
        if (count($errors) > 0) {
            return $this->json([
                'message' => 'Ошибка при вводе данных',
                'error' => $errors],
                422,
                ['charset=utf-8'],
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }

        try {
            $question = $questionService->saveWithVariant(new Question(), $data['question'], $data['variant'], $questionImage, $variantImages);
            $response = [
                'message' => 'Вопрос создан',
                'question' => $question,
            ];
            $status = 200;
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
                ['groups' => 'create',]
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }
    }
}
