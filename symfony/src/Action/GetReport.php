<?php

namespace App\Action;

use App\Entity\Result;
use App\Enum\Format;
use App\Handler\ReportHandler;
use App\Response\ErrorResponse;
use App\Response\SuccessResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetReport
{

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly ErrorResponse          $errorResponse,
        private readonly SuccessResponse        $successResponse,
        private readonly ReportHandler          $reportHandler,
    )
    {
    }

    public function get(Request $request, array $groups = ['report']): JsonResponse|Response
    {
        try {
            $data = json_decode($request->getContent(), true);
            $resultId = $request->attributes->get('_route_params', [])['id'];
            $result = $this->em->find(Result::class, $resultId);

            if ($result) {
                $result = $this->em->find(Result::class, $resultId);
            } else {
                throw new \Exception(sprintf('Результат с идентификатором %s не обнаружен', $resultId));
            }
            if (($data['format'] ?? null)) {
                $format = (Format::tryFrom($data['format'])?->name);
                if (!$format) {
                    throw new \Exception(sprintf('Формат %s не поддерживается', $data['format']));

                }
                return $this->successResponse->response(['content' => $this->reportHandler->build($result, $format, $groups)]);
            } else {
                throw new \Exception('Формат не передан');
            }

        } catch (\Exception $e) {
            return $this->errorResponse->response(['error' => $e->getMessage()]);
        }
    }
}