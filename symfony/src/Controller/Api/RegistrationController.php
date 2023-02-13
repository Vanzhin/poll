<?php

namespace App\Controller\Api;

use App\Factory\UserFactory;
use App\Service\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/api/register', name: 'app_api_register', methods: ['POST'])]
    public function register(
        Request                $request,
        EntityManagerInterface $entityManager,
        ValidationService      $validator,
        UserFactory            $userFactory
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = $userFactory->create($data['email'] ?? '', $data['password'] ?? '', $data['firstName'] ?? '');
        $errors = $validator->validate($user);
        if ($validator->userPasswordValidate($data['password'])){
            foreach($validator->userPasswordValidate($data['password']) as $error){
                $errors[] = $error;

            }
        }

        if ($data['password'] !== $data['confirmPassword']) {
            $errors[] = 'Пароли не совпадают.';
        }
        if (count($errors) > 0) {
            return $this->json([
                'message' => 'Ошибка при вводе данных',
                'error' => $errors],
                422,
                ['charset=utf-8'],
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } else {
            $entityManager->persist($user);
            $entityManager->flush();
        }
        return $this->json(['message' => 'Пользователь зарегистрирован'],
            200,
            ['charset=utf-8'],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);

    }
}
