<?php

namespace App\Security\Authentication;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

class AuthenticationFailureHandler implements AuthenticationFailureHandlerInterface
{
    public function __construct(private readonly UrlGeneratorInterface $urlGenerator)
    {
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        $email = $request->query->get('user');
        $session = $request->getSession();
        $session->getFlashBag()->add('login_failure', $exception->getMessageKey());
        return new RedirectResponse($this->urlGenerator->generate('app_login_link', ['savedEmail' => $email]));


    }
}