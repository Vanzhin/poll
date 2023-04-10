<?php

namespace App\Action\User;

use App\Entity\User;
use App\Handler\UserHandler;
use App\Response\Question\ErrorResponse;
use App\Response\Question\SuccessResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class EditUser
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly ErrorResponse          $errorResponse,
        private readonly SuccessResponse        $successResponse,
        private readonly Security               $security,
        private readonly UserHandler            $userHandler,

    )
    {
    }

    public function edit(Request $request): JsonResponse
    {
        try {
            $userId = $request->attributes->get('_route_params', [])['id'];
            $user = $this->em->find(User::class, $userId);

            if ($user) {
                if ((in_array('ROLE_ADMIN', $this->security->getUser()->getRoles())) or ($user === $this->security->getUser())) {
                    return $this->successResponse->response(['content' => json_encode($this->userHandler->edit($user, $request->request->all()))]);


                } else {
                    throw new \Exception(sprintf('Доступ для пользователя "%s" ограничен', $this->security->getUser()->getFirstName()), 401);
                }

            } else {
                throw new \Exception(sprintf('Пользователь с идентификатором %s не обнаружен', $userId));
            }

        } catch (\Exception $e) {
            return $this->errorResponse->response(['error' => $e->getMessage()], $e->getCode() ? $e->getCode() : 422);
        }

    }
}