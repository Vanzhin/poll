<?php

namespace App\Action\Analytics;

use App\Action\BaseAction;
use App\Entity\Analytics\Analytics;
use App\Entity\Analytics\YandexMetrika;
use App\Entity\Question;
use App\Repository\AnalyticsRepository;
use App\Repository\QuestionRepository;
use App\Service\Paginator;
use App\Service\SerializerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class YandexAnalyticsAction extends BaseAction
{
    private const TYPE = 'yandex';

    public function __construct(
        private readonly AnalyticsRepository $analyticsRepository,
        private readonly SerializerService   $serializer
    )
    {
        parent::__construct($serializer);

    }

    public function get(Request $request): JsonResponse
    {
        try {
            $type = $request->attributes->get('_route_params', [])['type'];

            if (!str_contains($type, self::TYPE)) {
                throw new \Exception(sprintf('Параметр поиска не содержит  "%s"', self::TYPE));

            }
            $analytics = $this->analyticsRepository->getAnalyticsByType($type);
            if ($analytics) {
                $yandexMetrics = YandexMetrika::create($this->analyticsRepository->getAnalyticsByType($type));

            } else {
                $yandexMetrics = null;
            }
            if ($yandexMetrics instanceof YandexMetrika) {
                return $this->successResponse($yandexMetrics, ['groups' => 'analytics']);

            } else {
                throw new \Exception(sprintf('Тип с названием %s не найден', $type));
            }

        } catch (\Exception $e) {
            return $this->errorResponse(['error' => $e->getMessage()]);
        }

    }
}