<?php

namespace App\Action\Security;

use App\Controller\Api\BaseAction\NewBaseAction;
use App\Factory\User\UserFactory;
use App\Repository\User\UserRepository;
use App\Response\AppException;
use App\Service\Mailer;
use App\Service\SerializerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;

class LinkLoginAction extends NewBaseAction
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserFactory            $userFactory,
        private readonly Mailer                 $mailer,
        private readonly UserRepository         $userRepository,
        SerializerService                       $serializer,

    )
    {
        parent::__construct($serializer);
    }

    public function sendLink(Request $request): JsonResponse
    {
        $email = $request->attributes->get('_route_params', [])['email'];
        if (!$this->IsValid($email)) {
            throw new AppException('Не верный формат почты');
        }
//            todo тут надо править логику типа пользователь может ввести как логин так и почту
//        если почта есть и уникальна, отправлять ссылку
//        если почта есть, не уникальна - сообщать об этом пользователю
//        если почты нет - создавать пользователя по почте
//        если есть логин, то отправлять на почту письмо, если логина нет, сообщать об этом
        $user = $this->userRepository->findOneBy(['email' => $email]);
        if (!$user) {
            $user = $this->userFactory->createBuilder()->buildUser($email);
            $this->em->persist($user);
            $this->em->flush();
        }
        $this->mailer->sendLoginLinkEmail($user);
        return $this->successResponse(['message' => 'Ссылка для входа в личный кабинет отправлена на ' . $user->getEmail()]);
    }
// перенести в валидатор и использовать везде
    private function IsValid(string $email): bool
    {
        if (!preg_match('/^[\w+\.]+@([\w-]+\.)+[\w-]{2,4}$/', $email)) {
            return false;
        }
        return true;
    }
}