<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use App\Service\FormService;
use App\Service\Paginator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/admin/user', name: 'app_admin_user')]
    public function index(Paginator $paginator, UserRepository $repository): Response
    {
        return $this->render('admin/user/index.html.twig',
            [
                'pagination' => $paginator->getPagination($repository->findLastUpdatedQuery(), 10)
            ]

        );
    }

    #[Route("admin/user/create", name: 'app_admin_user_create')]
    public function create(Request $request, EntityManagerInterface $em, FormService $formService): Response
    {
        $form = $this->createForm(UserFormType::class, new User());
        if ($formService->handle($form, $request, $em)) {
            $this->addFlash('user_flash', 'Пользователь создан');
            return $this->redirectToRoute('app_admin_user');
        }

        return $this->render('admin/user/create.html.twig', [
            'userForm' => $form->createView(),
            'buttonText' => 'Создать',
            'titleText' => 'Создание пользователя'
        ]);
    }


    #[Route("admin/user/{id}/edit", name: 'app_admin_user_edit')]
    public function edit(User $user, Request $request, EntityManagerInterface $em, FormService $formService): Response
    {
        $form = $this->createForm(UserFormType::class, $user);
        if ($formService->handle($form, $request, $em)) {
            $this->addFlash('user_flash', 'Пользователь обновлен');
            return $this->redirectToRoute('app_admin_user');
        }

        return $this->render('admin/user/create.html.twig', [
            'userForm' => $form->createView(),
            'buttonText' => 'Обновить',
            'titleText' => 'Обновление пользователя'
        ]);
    }

    #[Route("admin/user/{id}/delete", name: 'app_admin_user_delete')]
    public function delete(User $user, EntityManagerInterface $em): Response
    {

        $em->remove($user);
        $em->flush();
        $this->addFlash('user_flash', 'Пользователь удален');
        return $this->redirectToRoute('app_admin_user');

    }


}
