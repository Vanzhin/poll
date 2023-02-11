<?php

namespace App\Security\Authentication\Api;

use App\Service\SessionService;
use App\Service\TokenService;
use Doctrine\ORM\EntityManagerInterface;
use Gesdinet\JWTRefreshTokenBundle\Generator\RefreshTokenGeneratorInterface;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Signature\SignatureHasher;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

class AuthenticationSuccessHandler implements AuthenticationSuccessHandlerInterface
{

    public function __construct(
        private readonly SessionService $sessionService,
        private readonly TokenService $tokenService

    )
    {
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response
    {

        $refreshToken = $this->tokenService->getRefreshToken($token->getUser());
        $this->tokenService->saveRefreshToken($refreshToken);
        $this->sessionService->set($refreshToken->getRefreshToken(), 'refresh_token');

        return new RedirectResponse('/test/1');
    }
}