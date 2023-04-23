<?php

namespace App\Controller\Api;

use App\Action\Analytics\YandexAnalyticsAction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AnalyticsController extends AbstractController
{
    #[Route('/api/analytics/yandex/{type<\w+>?yandex}', name: 'app_api_analytics_yandex')]
    public function getYandex(Request $request, YandexAnalyticsAction $action): JsonResponse
    {
        return $action->get($request);
    }
}
