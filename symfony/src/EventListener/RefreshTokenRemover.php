<?php

namespace App\EventListener;

use App\Entity\RefreshToken;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::preFlush, method: 'preFlush', entity: RefreshToken::class)]
class RefreshTokenRemover
{

    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function preFlush(RefreshToken $token)
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['email' => $token->getUsername()]);
        $allUserTokens = $this->em->getRepository(RefreshToken::class)->findBy(['username' => $token->getUsername()]);
        foreach ($allUserTokens as $userToken) {
            if ($userToken->isValid()) {
                if (!in_array('ROLE_ADMIN', $user->getRoles())) {
                    $this->em->remove($userToken);
                }
            } else {
                $this->em->remove($userToken);

            }


        }
    }
}