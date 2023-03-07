<?php

namespace App\Controller\Api;

use App\Entity\Question;
use App\Entity\Variant;
use App\Service\QuestionService;
use App\Service\ValidationService;
use App\Service\VariantService;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VariantController extends AbstractController
{
    #[Route('/api/variant/{id}', name: 'app_api_variant_show', methods: ['GET'])]
    public function show(Variant $variant): JsonResponse
    {

        return $this->json(
            $variant,
            200,
            ['charset=utf-8'],
            ['groups' => 'admin'],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    #[Route('/api/variant/create', name: 'app_api_variant_create', methods: 'POST')]
    public function create(Request $request, VariantService $variantService, ValidationService $validation, EntityManagerInterface $em, QuestionService $questionService): JsonResponse
    {
        $data = $request->request->all();
        $image = $request->files->get('variantImage');
        $variant = $variantService->save(new Variant(), $data['variant']);

        $errors = $validation->entityWithImageValidate($variant, $image);
        if (!is_null($errors) && count($errors) > 0) {
            return $this->json([
                'message' => 'Ошибка при вводе данных',
                'error' => $errors],
                422,
                ['charset=utf-8'],
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }

        $response = $variantService->saveResponse($variant, $image);
        return $this->json($response['response'],
            $response['status'],
            ['charset=utf-8'],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);

    }

    #[Route('/api/variant/{id}/edit', name: 'app_api_variant_edit', methods: 'POST')]
    public function edit(Variant $variant, Request $request, VariantService $variantService, ValidationService $validation): JsonResponse
    {
        $data = $request->request->all();
        $image = $request->files->get('variantImage');
        $variant = $variantService->save($variant, $data['variant']);

        $errors = $validation->entityWithImageValidate($variant, $image);
        if (!is_null($errors) && count($errors) > 0) {
            return $this->json([
                'message' => 'Ошибка при вводе данных',
                'error' => $errors],
                422,
                ['charset=utf-8'],
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }

        $response = $variantService->saveResponse($variant, $image);
        return $this->json($response['response'],
            $response['status'],
            ['charset=utf-8'],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    #[Route("api/variant/{id}/delete", name: 'app_api_variant_delete')]
    public function delete(Variant $variant, VariantService $variantService): Response
    {
        try {
            $variantService->delete($variant);
            $response = [
                'message' => 'Вариант удален',
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

    #[Route('/api/variant/create_many', name: 'app_api_variant_create_many', methods: 'POST')]
    public function createMany(Request $request, VariantService $variantService, ValidationService $validation, EntityManagerInterface $em): JsonResponse
    {
        $data = $request->request->all();
        $images = $request->files->get('variantImage');
        $errors = $validation->manyVariantsValidate($data ?? [], $images);
        if (!is_null($errors) && count($errors) > 0) {
            return $this->json([
                'message' => 'Ошибка при вводе данных',
                'error' => $errors],
                422,
                ['charset=utf-8'],
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }

        try {
            $question = $em->find(Question::class, $data['questionId']);
            $answers = $question->getAnswer();
            foreach ($data['variant'] as $key => $variantData) {
                $image = $images[$key] ?? null;
                $variantData['questionId'] = $data['questionId'];
                $variant = $variantService->save(new Variant(), $variantData ?? [], $image);
                if ($question->getType()->getTitle() === 'order') {
                    $answers[] = $variant->getId();
                }
            }
            if ($question->getType()->getTitle() === 'order') {
                $question->setAnswer($answers);
                $em->persist($question);
                $em->flush();
            }
            $response = [
                'message' => 'Варианты созданы',
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
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }

    }
}
