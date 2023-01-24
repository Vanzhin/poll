<?php

namespace App\Controller\Api;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;


class ArticleController extends AbstractController
{
    #[Route('/api/article', name: 'app_api_article', methods: ['GET'])]
    public function index(ArticleRepository $repository): JsonResponse
    {
        $articles = $repository->findAll();

        return $this->json(
            $articles,
            200,
            ['charset=utf-8'],
            ['groups' => 'main'],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    #[Route('/api/article/{slug}', name: 'app_api_article_show', methods: ['GET'])]
    public function show(Article $article): JsonResponse
    {

        return $this->json(
            $article,
            200,
            ['charset=utf-8'],
            ['groups' => 'main'],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
