<?php

namespace App\Controller\Api;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Service\Paginator;
use App\Twig\Extension\AppUpLoadedAsset;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

class CategoryController extends AbstractController
{
    #[Route('/api/category', name: 'app_api_category_index')]
    public function index(Request $request, AppUpLoadedAsset $upLoadedAsset, Paginator $paginator, CategoryRepository $repository): JsonResponse
    {
        $parentId = $request->query->get('parent');

        $dateCallback = function ($key, $innerObject, string $attributeName) use ($upLoadedAsset) {
            if ($attributeName === 'image' && !is_null($key)) {
                if ($innerObject instanceof Category) {
                    return $upLoadedAsset->asset('category_upload_url', $key);

                }
            };
        };
        $pagination = $paginator->getPagination($repository->findAllChildrenQuery(!is_null($parentId) && !is_null($repository->find($parentId)) ? $parentId : null));
        if ($pagination->count() > 0) {
            $response['category'] = $pagination;

        }
        $response['pagination'] = $paginator->getInfo($pagination);
        $parent = !is_null($parentId) ? $repository->find($parentId) : null;
        if (isset($parent)) {
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
                'groups' => ['main', 'category'],
                AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
                AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true,
                AbstractNormalizer::CALLBACKS => [
                    'image' => $dateCallback,
                ]
            ],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    #[Route('/api/category/{id}/children', name: 'app_api_category_children')]
    public function getChildren(Category $category, AppUpLoadedAsset $upLoadedAsset, EntityManagerInterface $em): JsonResponse
    {
        $dateCallback = function ($key, $innerObject, string $attributeName) use ($upLoadedAsset) {
            if ($attributeName === 'image' && !is_null($key)) {
                if ($innerObject instanceof Category) {
                    return $upLoadedAsset->asset('category_upload_url', $key);

                }
            };
        };
        return $this->json(
            $category->getChildren(),
            200,
            ['charset=utf-8'],
            [
                'groups' => 'main',
                AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
                AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true,
                AbstractNormalizer::CALLBACKS => [
                    'image' => $dateCallback,
                ]
            ],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
