<?php

namespace App\Controller\Api;

use App\Entity\Question;
use App\Factory\Question\QuestionFactory;
use App\Service\FileUploader;
use App\Service\NormalizerService;
use App\Service\QuestionService;
use App\Service\ValidationService;
use App\Service\VariantService;
use App\Twig\Extension\AppUpLoadedAsset;
use Doctrine\ORM\EntityManagerInterface;
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
}
