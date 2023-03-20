<?php

namespace App\Controller\Api\Admin;

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
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

class VariantController extends AbstractController
{
    #[Route('/api/admin/variant/{id}', name: 'app_api_admin_variant_show', methods: ['GET'])]
    public function show(Variant $variant, AppUpLoadedAsset $upLoadedAsset, NormalizerService $normalizerService): JsonResponse
    {

        return $this->json(
            $variant,
            200,
            ['charset=utf-8'],
            ['groups' => 'admin',
                AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
                AbstractNormalizer::CALLBACKS => [
                    'image' => $normalizerService->imageCallback($upLoadedAsset),
                ]
            ],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    #[Route('/api/admin/variant/create', name: 'app_api_admin_variant_create', methods: 'POST')]
    public function create(Request $request, VariantService $variantService, ValidationService $validation, EntityManagerInterface $em, QuestionService $questionService): JsonResponse
    {
        $data = $request->request->all();
        $image = $request->files->get('variantImage');
        $variant = $variantService->make(new Variant(), $data['variant'] ?? []);

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

    #[Route('/api/admin/variant/{id}/edit', name: 'app_api_admin_variant_edit', methods: 'POST')]
    public function edit(Variant $variant, Request $request, VariantService $variantService, ValidationService $validation): JsonResponse
    {
        $data = $request->request->all();
        $image = $request->files->get('variantImage', false);
        $variant = $variantService->make($variant, $data['variant'] ?? []);

        $errors = $validation->entityWithImageValidate($variant, $image instanceof UploadedFile ? $image : null);
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

    #[Route("api/admin/variant/{id}/delete", name: 'app_api_admin_variant_delete')]
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

    #[Route('/api/admin/variant/create_many', name: 'app_api_admin_variant_create_many', methods: 'POST')]
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
                $variant = $variantService->make(new Variant(), $variantData ?? [], $image);
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
