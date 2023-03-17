<?php

namespace App\Controller\Api\Admin;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\TestRepository;
use App\Service\CategoryService;
use App\Service\FileUploader;
use App\Service\NormalizerService;
use App\Service\Paginator;
use App\Service\ValidationService;
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

class CategoryController extends AbstractController
{
    #[Route('/api/admin/category', name: 'app_api_admin_category_index')]
    public function index(Request $request, AppUpLoadedAsset $upLoadedAsset, Paginator $paginator, CategoryRepository $categoryRepository, TestRepository $testRepository, NormalizerService $normalizerService): JsonResponse
    {
        $parentId = $request->query->get('parent');
        $pagination = $paginator->getPagination($categoryRepository->findAllLatestChildrenQuery(!is_null($parentId) && !is_null($categoryRepository->find($parentId)) ? $parentId : null));
        if ($pagination->count() > 0) {
            $response['children'] = $pagination;

        }
        $parent = !is_null($parentId) ? $categoryRepository->find($parentId) : null;
        if (isset($parent)) {
            $category = $categoryRepository->find($parent);
            $response['parent'] = $category;

            $test = $parent->getTest();
            if (count($test) > 0) {
                $pagination = $paginator->getPagination($testRepository->findLastUpdatedByCategoryQuery($parent));
                foreach ($pagination as $test) {
                    $test->setQuestionCount($testRepository->getQuestionCount($test));
                    $test->setSectionCount($testRepository->getSectionCount($test));
                    $test->setTicketCount($testRepository->getTicketCount($test));
                }
                $response['test'] = $pagination;

            }
        }
        $response['pagination'] = $paginator->getInfo($pagination);

        return $this->json(
            $response,
            200,
            ['charset=utf-8'],
            [
                'groups' => ['category'],
                AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
                AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true,
                AbstractNormalizer::CALLBACKS => [
                    'image' => $normalizerService->imageCallback($upLoadedAsset),
                ]
            ],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }


    #[Route('/api/admin/category/{id}', name: 'app_api_admin_category_show', methods: ['GET'])]
    public function show(Category $category, AppUpLoadedAsset $upLoadedAsset, NormalizerService $normalizerService): JsonResponse
    {
        return $this->json(
            $category,
            200,
            ['charset=utf-8'],
            [
                'groups' => 'admin',
                AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
                AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true,
                AbstractNormalizer::CALLBACKS => [
                    'image' => $normalizerService->imageCallback($upLoadedAsset),
                ]
            ],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }


    #[Route('/api/admin/category/create', name: 'app_api_admin_category_create', methods: 'POST')]
    public function create(Request $request, ValidationService $validation, CategoryService $categoryService): JsonResponse
    {
        $data = $request->request->all();
        $image = $request->files->get('categoryImage');
        $category = $categoryService->make(new Category(), $data);
        $errors = $validation->entityWithImageValidate($category, $image);
        if (!is_null($errors) && count($errors) > 0) {
            return $this->json([
                'message' => 'Ошибка при вводе данных',
                'error' => $errors],
                422,
                ['charset=utf-8'],
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }

        $response = $categoryService->saveResponse($category, $image);
        return $this->json($response['response'],
            $response['status'],
            ['charset=utf-8'],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    #[Route('/api/admin/category/{id}/edit', name: 'app_api_admin_category_update', methods: 'POST')]
    public function edit(Category $category, Request $request, ValidationService $validation, CategoryService $categoryService): JsonResponse
    {
        $data = $request->request->all();
        $image = $request->files->get('categoryImage', false);
        $category = $categoryService->make($category, $data);
        $errors = $validation->entityWithImageValidate($category, $image instanceof UploadedFile ? $image : null);
        if (!is_null($errors) && count($errors) > 0) {
            return $this->json([
                'message' => 'Ошибка при вводе данных',
                'error' => $errors],
                422,
                ['charset=utf-8'],
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }
        $response = $categoryService->saveResponse($category, $image);
        return $this->json($response['response'],
            $response['status'],
            ['charset=utf-8'],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    #[Route("api/admin/category/{id}/delete", name: 'app_api_admin_category_delete')]
    public function delete(Category $category, CategoryService $categoryService, FileUploader $categoryImageUploader, EntityManagerInterface $em): Response
    {
        try {
            $categoryService->delete($category, $categoryImageUploader, $em);

            $response = [
                'message' => 'Раздел удален',
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

    #[Route("api/admin/category/{id}/image_delete", name: 'app_api_admin_category_image_delete')]
    public function imageDelete(Category $category, CategoryService $categoryService, FileUploader $categoryImageUploader, EntityManagerInterface $em): JsonResponse
    {
        try {
            $categoryService->imageDelete($category, $categoryImageUploader, $em);

            $response = [
                'message' => 'Фото удалено',
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
}
