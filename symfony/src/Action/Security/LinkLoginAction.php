<?php

namespace App\Action\Security;

use App\Action\BaseAction;
use App\Factory\MinTrudTest\MinTrudTestBuilder;
use App\Factory\User\UserFactory;
use App\Repository\UserRepository;
use App\Service\Mailer;
use App\Service\SerializerService;
use App\Service\ValidationService;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class LinkLoginAction extends BaseAction
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserFactory            $userFactory,
        private readonly Mailer                 $mailer,
        private readonly UserRepository         $userRepository,
        private readonly SerializerService      $serializer

    )
    {
        parent::__construct($serializer);
    }

    public function sendLink(Request $request): JsonResponse
    {

        try {
            $email = $request->attributes->get('_route_params', [])['email'];
            if (!$this->IsValid($email)) {
                throw new \Exception('Не верный формат почты');
            }
            $user = $this->userRepository->findOneBy(['email' => $email]);
            if (!$user) {
                $user = $this->userFactory->createBuilder()->buildUser($email);
                $this->em->persist($user);
                $this->em->flush();
            }
            $this->mailer->sendLoginLinkEmail($user);
            return $this->successResponse(['message' => 'Ссылка для входа в личный кабинет отправлена на ' . $user->getEmail()]);

        } catch (\Exception $e) {

            return $this->errorResponse(["error" => $e->getMessage()]);

        }
    }

    private function IsValid(string $email): bool
    {
        if (!preg_match('/^[\w+\.]+@([\w-]+\.)+[\w-]{2,4}$/', $email)) {
            return false;
        }
        return true;
    }
}