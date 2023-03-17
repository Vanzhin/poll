<?php

namespace App\Controller\Api;

use App\Repository\ResultRepository;
use App\Service\NormalizerService;
use App\Service\Paginator;
use App\Twig\Extension\AppUpLoadedAsset;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

class AccountController extends AbstractController
{
    #[Route('/api/auth/account', name: 'app_api_auth_account')]
    public function index(): JsonResponse
    {
        return $this->json(
            $this->getUser(),
            200,
            ['charset=utf-8'],
            ['groups' => 'account'],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    #[Route('/api/auth/user', name: 'app_api_auth_user')]
    public function getUserInfo(): JsonResponse
    {
        return $this->json(
            $this->getUser(),
            200,
            ['charset=utf-8'],
            ['groups' => 'user'],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    #[Route('/api/auth/result', name: 'app_api_auth_result')]
    public function getResult(AppUpLoadedAsset $upLoadedAsset, NormalizerService $normalizerService, Paginator $paginator, ResultRepository $resultRepository): JsonResponse
    {
        $pagination = $paginator->getPagination($resultRepository->findLastUpdatedByUserQuery($this->getUser()), 10);
        if ($pagination->count() > 0) {
            foreach ($pagination as $result) {
                $result->setQuestionCount($result->getAnswers()->count());
            }
            $response['results'] = $pagination;
        }

        $response['pagination'] = $paginator->getInfo($pagination);
        return $this->json(
            $response,
            200,
            ['charset=utf-8'],
            [
                'groups' => 'result',
                AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
                AbstractNormalizer::CALLBACKS => [
                    'image' => $normalizerService->imageCallback($upLoadedAsset),
                ]
            ],
        );

    }
}
