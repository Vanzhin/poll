<?php

namespace App\Action\Security;

use App\Controller\Api\BaseAction\NewBaseAction;
use App\Factory\User\UserFactory;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Response\AppException;
use App\Service\Mailer;
use App\Service\SerializerService;
use App\Service\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Serializer;

class LinkLoginAction extends NewBaseAction
{
    public function __construct(
        private readonly EntityManagerInterface  $em,
        private readonly UserFactory             $userFactory,
        private readonly Mailer                  $mailer,
        private readonly UserRepositoryInterface $userRepository,
        private readonly ValidationService       $validation,
        SerializerService                        $serializer,

    )
    {
        parent::__construct($serializer);
    }

    public function sendLink(Request $request): JsonResponse
    {

        $username = $request->attributes->get('_route_params', [])['username'];
        $user = null;
//        сначала ищу по логину
        if (!$user) {
            $user = $this->userRepository->findOneByLogin($username);
        }
//        затем ищу по почте
        if ($this->validation->IsValid($username, 'email') && !$user) {
            $userList = $this->userRepository->findAllByEmail($username);
            //        если почта есть, не уникальна - сообщать об этом пользователю
            if (count($userList) > 1) {
                throw new AppException('Указанная почта не уникальна.');
            };
            //        если почта есть и уникальна, отправлять ссылку

            if (count($userList) === 1) {
                $user = current($userList);
            };
            //        если почты нет - создавать пользователя по почте

            if (!$user) {
                $user = $this->userFactory->createBuilder()->buildUser($username);
                $errors = $this->validation->validate($user);
                if ($errors) {
                    throw new AppException(implode(', ', $errors));
                }
                $this->em->persist($user);
                $this->em->flush();
            }
        }
//        если есть логин, то отправлять на почту письмо, если логина нет, сообщать об этом
        if (!$user) {
            throw new AppException('Не верные почта или логин.');

        }
        $this->mailer->sendLoginLinkEmail($user);
        return $this->successResponse([], [], 'Ссылка для входа в личный кабинет отправлена на ' . $user->getEmail());
    }
}