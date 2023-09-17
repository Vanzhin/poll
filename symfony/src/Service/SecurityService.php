<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User\User;
use Symfony\Component\Security\Http\LoginLink\LoginLinkHandlerInterface;

class SecurityService
{
    public function __construct(
        //        $host - определяю в services.yaml
        private readonly string                    $host,
        private readonly LoginLinkHandlerInterface $loginLinkHandler,
    )
    {
    }

    public function getLoginLink(User $user): string
    {
        return preg_replace('/^(?:https?:\/\/)?(?:[^@\/\n]+@)?(?:www\.)?([^:\/\n]+)/im', $this->host, $this->loginLinkHandler->createLoginLink($user)->getUrl());
    }
}