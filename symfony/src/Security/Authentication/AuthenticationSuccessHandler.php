<?php

namespace App\Security\Authentication;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function __construct(private readonly UrlGeneratorInterface $urlGenerator)
    {
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response
    {

        $session = $request->getSession();
        $session->getFlashBag()->add('login_success', 'Успешная авторизация по ссылке');
        return new RedirectResponse($this->urlGenerator->generate('app_account'));
    }
}