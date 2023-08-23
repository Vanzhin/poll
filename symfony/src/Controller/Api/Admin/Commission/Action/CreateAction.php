<?php
declare(strict_types=1);

namespace App\Controller\Api\Admin\Commission\Action;

use App\Controller\Api\BaseAction\NewBaseAction;
use App\Entity\Company;
use App\Factory\Commission\CommissionFactory;
use App\Response\AppException;
use App\Service\SerializerService;
use App\Service\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CreateAction extends NewBaseAction
{
    public function __construct(
        SerializerService                       $serializer,
        private readonly EntityManagerInterface $entityManager,
        private readonly ValidationService      $validator,
        private readonly CommissionFactory      $factory,
    )
    {
        parent::__construct($serializer);
    }

    public function run(Request $request, Company $company): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $commission = $this->factory->createBuilder()->build($data, $company);
        if ($errors = $this->validator->validate($commission)) {
            throw new AppException(implode(', ', $errors));
        }
        $participants = $commission->getParticipant();
        if($participants->count() !== $participants->filter(fn($participant) => ($participant->getCompany() === $company))->count()){
            throw new AppException('Нельзя принимать членов комиссии из другой компании');

        };

        $this->entityManager->persist($commission);
        $this->entityManager->flush();

        return $this->successResponse($commission, ['admin_commission'], 'Комиссия создана');
    }
}