<?php

namespace App\Action\Company;

use App\Action\BaseAction;
use App\Entity\Company;
use App\Factory\Company\CompanyFactory;
use App\Service\SerializerService;
use App\Service\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UpdateAction extends BaseAction
{
    public function __construct(
        SerializerService                       $serializer,
        private readonly EntityManagerInterface $entityManager,
        private readonly ValidationService      $validator,
        private readonly CompanyFactory         $companyFactory,
    )
    {
        parent::__construct($serializer);
    }

    public function run(Company $company, Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $updated = $this->companyFactory->createBuilder()->buildCompany($data, $company);
            $errors = $this->validator->validate($updated);

            if ($errors) {
                throw new \Error(implode(', ', $errors));
            }
            $this->entityManager->persist($updated);
            $this->entityManager->flush();

            return $this->successResponse($updated, ['admin_user']);

        } catch (\Exception|\Error $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}