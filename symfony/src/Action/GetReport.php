<?php

namespace App\Action;

use App\Entity\Result;
use App\Entity\User;
use App\Enum\Format;
use App\Factory\Organization\OrganizationFactory;
use App\Factory\WorkerCard\WorkerCardFactory;
use App\Handler\ReportHandler;
use App\Response\ErrorResponse;
use App\Response\SuccessResponse;
use App\Service\ValidationService;
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
        private readonly WorkerCardFactory      $workerCardFactory,
        private readonly ValidationService      $validation,
        private readonly OrganizationFactory    $organizationFactory,
    )
    {
    }

    public function get(Request $request, array $groups = ['report']): JsonResponse|Response
    {
        try {
            $data = json_decode($request->getContent(), true);
            $resultId = $request->attributes->get('_route_params', [])['id'];
            $result = $this->em->find(Result::class, $resultId);
            if ($result?->getTest()->getMinTrudTest()) {
                $result = $this->em->find(Result::class, $resultId);
            } else {
                throw new \Exception(sprintf('Результат с идентификатором %s не обнаружен/ Не обнаружен соответствующий тест МИНТРУД', $resultId));
            }
            if (($data['format'] ?? null)) {
                $format = (Format::tryFrom($data['format'])?->name);
                if (!$format) {
                    throw new \Exception(sprintf('Формат %s не поддерживается', $data['format']));

                }
                $errors = [];
//todo костыль убрать
                $user = $result->getUser();
                $workerCard = $this->workerCardFactory->createBuilder()->buildWorkerCard($data['worker']);

                $workerOrganization = $this->organizationFactory->createBuilder()
                    ->buildOrganization([
                        'inn' => intval($data['worker']['employerInn'] ?? ''),
                        'title' => $data['worker']['employerTitle'] ?? ''
                    ]);
                if (count($this->validation->validate($workerOrganization)) > 0) {
                    $errors[] = implode(', ', $this->validation->validate($workerOrganization));

                };

                $organization = $this->organizationFactory->createBuilder()
                    ->buildOrganization([
                        'inn' => intval($data['organization']['inn'] ?? ''),
                        'title' => $data['organization']['title'] ?? ''
                    ]);
                if (count($this->validation->validate($organization)) > 0) {
                    $errors[] = implode(', ', $this->validation->validate($organization));

                };
                $workerCard->setOrganization($workerOrganization);

                if (count($this->validation->validate($workerCard)) > 0) {
                    $errors[] = implode(', ', $this->validation->validate($workerCard));

                };
                if (count($errors) > 0) {
                    throw new \Exception(implode(', ', $errors));

                }
                /** @var User $user */
                $user->setWorkerCard($workerCard);

                return $this->successResponse->response(['content' => $this->reportHandler->build($result, $organization, $format, $groups)]);

            } else {
                throw new \Exception('Формат не передан');
            }

        } catch (\Exception $e) {
            return $this->errorResponse->response(['error' => $e->getMessage()]);
        }
    }
}