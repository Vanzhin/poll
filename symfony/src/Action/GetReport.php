<?php

namespace App\Action;

use App\Entity\MinTrudTest;
use App\Entity\Result;
use App\Enum\Format;
use App\Factory\Organization\OrganizationFactory;
use App\Factory\User\UserFactory;
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
        private readonly UserFactory            $userFactory,
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
                $errors = [];
                $user = $this->userFactory->createBuilder()->updateUser($result->getUser(), $data['worker'] ?? []);

                if (count($this->validation->validate($user)) > 0) {
                    $errors[] = implode(', ', $this->validation->validate($user));

                };
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
                if (count($errors) > 0) {
                    throw new \Exception(implode(', ', $errors));

                }
                $user->setOrganization($workerOrganization);

                return $this->successResponse->response(['content' => $this->reportHandler->build($result, $organization, $format, $groups)]);

            } else {
                throw new \Exception('Формат не передан');
            }

        } catch (\Exception $e) {
            return $this->errorResponse->response(['error' => $e->getMessage()]);
        }
    }
}