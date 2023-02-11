<?php

namespace App\Controller\Api;

use App\Factory\UserFactory;
use App\Service\Mailer;
use App\Service\ValidationService;
use Doctrine\DBAL\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    #[Route('/api/login_link/{email}', name: 'app_api_login_link')]
    public function index(string $email, ValidationService $validator, UserFactory $userFactory, Mailer $mailer): JsonResponse
    {
        if (!$validator->isValid($email, 'email')) {
            return $this->json(
                [
                    'error' => 'Указан не верный формат email.',
                    'email' => $email
                ],
                200,
                ['charset=utf-8'],
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);

        }
            $user = $userFactory->create($email);

            try {

                $mailer->sendLoginLinkEmail($user);
                $data = ['message' => 'Ссылка для входа в личный кабинет отправлена на ' . $user->getEmail()];

            } catch (Exception $e) {
                $data = ['error' => 'При отправке сообщения произошла ошибка. Пожалуйста, повторите попытку позже.'];

            } finally {
                return $this->json($data,
                    200,
                    ['charset=utf-8'],
                )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
            }
    }
    #[Route('/api/login_link_check', name: 'app_api_login_link_check')]
    public function check(Request $request)
    {
        throw new \LogicException('This code should never be reached');
    }


}
