<?php

namespace App\Controller\Api\Admin;

use App\Entity\User;
use App\Factory\User\UserFactory;
use App\Repository\UserRepository;
use App\Service\Paginator;
use App\Service\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

class AccountController extends AbstractController
{
    #[Route('/api/admin/account', name: 'app_api_admin_account')]
    public function index(Paginator $paginator, UserRepository $repository): JsonResponse
    {
        $pagination = $paginator->getPagination($repository->findLatestQuery());
        if ($pagination->count() > 0) {
            $response['user'] = $pagination;

        }
        $response['pagination'] = $paginator->getInfo($pagination);
        return $this->json(
            $response,
            200,
            ['charset=utf-8'],
            [
                'groups' => 'admin_user',
                AbstractObjectNormalizer::SKIP_NULL_VALUES => true,
            ],
        )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    #[Route('/api/admin/account/{id}/edit', name: 'app_api_admin_account_edit', methods: 'POST')]
    public function edit(User $user, Request $request, ValidationService $validation, EntityManagerInterface $em, UserFactory $userFactory): JsonResponse
    {
        $data = $request->request->all();

        $user = $userFactory->createBuilder()->updateUserRole($data, $user);

        if (count($validation->validate($user)) > 0) {
            return $this->json([
                'message' => 'Ошибка при вводе данных',
                'error' => $validation->validate($user)],
                422,
                ['charset=utf-8'],
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }

        try {
            $em->persist($user);
            $em->flush();
            $response = [
                'message' => 'Пользователь обновлен',
                'userId' => $user->getId()
            ];
            $status = 200;
        } catch (\Exception $e) {
            $response = ['error' => $e->getMessage()];
            $status = 501;
        } finally {
            return $this->json($response,
                $status,
                ['charset=utf-8'],
            )->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }
    }

}
