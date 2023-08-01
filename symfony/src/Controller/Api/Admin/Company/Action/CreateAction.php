<?php

namespace App\Controller\Api\Admin\Company\Action;

use App\Controller\Api\BaseAction\NewBaseAction;
use App\Event\CompanyCreatedEvent;
use App\Factory\Company\CompanyFactory;
use App\Factory\User\UserFactory;
use App\Response\AppException;
use App\Service\SerializerService;
use App\Service\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class CreateAction extends NewBaseAction
{
    public function __construct(
        SerializerService                         $serializer,
        private readonly EntityManagerInterface   $entityManager,
        private readonly ValidationService        $validator,
        private readonly UserFactory              $userFactory,
        private readonly CompanyFactory           $companyFactory,
        private readonly EventDispatcherInterface $dispatcher
    )
    {
        parent::__construct($serializer);
    }

    public function run(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = $this->userFactory
            ->createBuilder()->buildUser($data['email'] ?? '', $data['login']);
        $errors = $this->validator->validate($user);

        if ($errors) {
            throw new AppException(implode(', ', $errors));
        }
        $user->setRoles(['ROLE_ADMIN']);
        $data['user'] = $user;
        $company = $this->companyFactory->createBuilder()->buildCompany($data);
        $errors = $this->validator->validate($company);
        $user->setCompany($company);

        if ($errors) {
            throw new AppException(implode(', ', $errors));
        }

        $this->entityManager->persist($user);
        $this->entityManager->persist($company);
        $this->entityManager->flush();

        $event = new CompanyCreatedEvent($company);
        $this->dispatcher->dispatch($event, CompanyCreatedEvent::NAME);

        return $this->successResponse($company, ['admin_user'], 'Компания создана');
    }
}