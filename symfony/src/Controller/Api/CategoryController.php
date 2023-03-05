<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Service\NormalizerService;
use App\Service\Paginator;
use App\Twig\Extension\AppUpLoadedAsset;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

class CategoryController extends AbstractController
{
    #[Route('/api/category', name: 'app_api_category_index')]
    public function index(Request $request, AppUpLoadedAsset $upLoadedAsset, Paginator $paginator, CategoryRepository $repository, NormalizerService $normalizerService): JsonResponse
    {
        $parentId = $request->query->get('parent');
        $pagination = $paginator->getPagination($repository->findAllChildrenQuery(!is_null($parentId) && !is_null($repository->find($parentId)) ? $parentId : null));
        if ($pagination->count() > 0) {
            $response['children'] = $pagination;

        }
        $response['pagination'] = $paginator->getInfo($pagination);
        $parent = !is_null($parentId) ? $repository->find($parentId) : null;
        if (isset($parent)) {
            $category = $repository->find($parent);
            $response['parent'] = $category;

            $test = $parent->getTest();
            if ($test) {
                $response['test'] = $test;
                unset($response['pagination']);
            }
        }
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

    #[Route('/api/category/{id}/children', name: 'app_api_category_children')]
    public function getChildren(Category $category, AppUpLoadedAsset $upLoadedAsset, NormalizerService $normalizerService): JsonResponse
    {
        return $this->json(
            $category->getChildren(),
            200,
            ['charset=utf-8'],
            [
                'groups' => 'main',
                AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
                AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true,
                AbstractNormalizer::CALLBACKS => [
                    'image' => $normalizerService->imageCallback($upLoadedAsset),
                ]
            ],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
