<?php

namespace App\Service;

use Gesdinet\JWTRefreshTokenBundle\Generator\RefreshTokenGeneratorInterface;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenInterface;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class TokenService
{

    const REFRESH_TOKEN_TTL = 604800;

    public function __construct(
        private readonly JWTTokenManagerInterface       $JWTManager,
        private readonly RefreshTokenGeneratorInterface $refreshTokenGenerator,
        private readonly RefreshTokenManagerInterface   $refreshTokenManager
    )
    {
    }

    public function createToken(UserInterface $user): string
    {
        return $this->JWTManager->create($user);
    }

    public function getRefreshToken(UserInterface $user, int $ttl = self::REFRESH_TOKEN_TTL): RefreshTokenInterface
    {
        return $this->refreshTokenGenerator->createForUserWithTtl($user, $ttl);
    }

    public function saveRefreshToken(RefreshTokenInterface $refreshToken): void
    {
        $this->refreshTokenManager->save($refreshToken);
    }

}