<?php

namespace App\Controller\Api;

use App\Action\Analytics\YandexAnalyticsAction;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AnalyticsController extends AbstractController
{
    #[Route('/api/analytics/yandex/{type<\w+>?yandex}', name: 'app_api_analytics_yandex')]
    public function getYandex(Request $request, YandexAnalyticsAction $action): JsonResponse
    {
        throw new \Exception('test everybody');
        return $action->get($request);
    }
}
