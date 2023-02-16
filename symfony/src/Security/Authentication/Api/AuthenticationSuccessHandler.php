<?php

namespace App\Security\Authentication\Api;

use App\Service\SessionService;
use App\Service\TokenService;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{

    public function __construct(
        private readonly SessionService $sessionService,
        private readonly TokenService $tokenService,

    )
    {
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response
    {

        $refreshToken = $this->tokenService->getRefreshToken($token->getUser());
        $this->tokenService->saveRefreshToken($refreshToken);
        $this->sessionService->set($refreshToken->getRefreshToken(), 'refresh_token');

        return new RedirectResponse('/redirection/' . $refreshToken->getRefreshToken());
    }
}