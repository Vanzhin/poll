<?php

namespace App\Controller\Api\Admin;

use App\Entity\Category;
use App\Service\CategoryService;
use App\Service\ValidationService;
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

class CategoryController extends AbstractController
{
    #[Route('/api/admin/category', name: 'app_api_admin_category_index')]
    public function index(AppUpLoadedAsset $upLoadedAsset, EntityManagerInterface $em): JsonResponse
    {
        $categories = $em->getRepository(Category::class)->findBy(['parent' => null]);
        $dateCallback = function ($key, $innerObject, string $attributeName) use ($upLoadedAsset) {
            if ($attributeName === 'image' && !is_null($key)) {
                if ($innerObject instanceof Category) {
                    return $upLoadedAsset->asset('category_upload_url', $key);

                }
            };
        };

        return $this->json(
            $categories,
            200,
            ['charset=utf-8'],
            [
                'groups' => 'admin',
                AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
                AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true,
                AbstractNormalizer::CALLBACKS => [
                    'image' => $dateCallback,
                ]
            ],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }


    #[Route('/api/admin/category/{id}', name: 'app_api_admin_category_show', methods: ['GET'])]
    public function show(Category $category, AppUpLoadedAsset $upLoadedAsset): JsonResponse
    {
        $dateCallback = function ($key, $innerObject, string $attributeName) use ($upLoadedAsset) {
            if ($attributeName === 'image' && !is_null($key)) {
                if ($innerObject instanceof Category) {
                    return $upLoadedAsset->asset('category_upload_url', $key);

                }
            };
        };

        return $this->json(
            $category,
            200,
            ['charset=utf-8'],
            [
                'groups' => 'admin',
                AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
                AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true,
                AbstractNormalizer::CALLBACKS => [
                    'image' => $dateCallback,
                ]
            ],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }


    #[Route('/api/admin/category/create', name: 'app_api_admin_category_create', methods: 'POST')]
    public function create(Request $request, ValidationService $validation, CategoryService $categoryService): JsonResponse
    {
        $data = $request->request->all();
        $image = $request->files->get('categoryImage');
        $errors = $validation->categoryValidate($data ?? [], $image);
        if (!is_null($errors) && count($errors) > 0) {
            return $this->json([
                'message' => 'Ошибка при вводе данных',
                'error' => $errors],
                422,
                ['charset=utf-8'],
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }

        try {
            $category = $categoryService->save(new Category(), $data ?? [], $image);
            $response = [
                'message' => 'Раздел создан',
                'categoryId' => $category->getId()
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

    #[Route('/api/admin/category/{id}/edit', name: 'app_api_admin_category_update', methods: 'POST')]
    public function edit(Category $category, Request $request, ValidationService $validation, CategoryService $categoryService): JsonResponse
    {
        $data = $request->request->all();
        $image = $request->files->get('categoryImage');
        $errors = $validation->categoryValidate($data ?? [], $image);
        if (!is_null($errors) && count($errors) > 0) {
            return $this->json([
                'message' => 'Ошибка при вводе данных',
                'error' => $errors],
                422,
                ['charset=utf-8'],
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }

        try {
            $category = $categoryService->save($category, $data ?? [], $image);
            $response = [
                'message' => 'Раздел обновлен',
                'categoryId' => $category->getId()
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

    #[Route("api/admin/category/{id}/delete", name: 'app_api_admin_category_delete')]
    public function delete(Category $category, CategoryService $categoryService): Response
    {
        try {
            $categoryService->delete($category);

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

}
