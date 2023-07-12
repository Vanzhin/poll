<?php

namespace App\Security\Voter;

use App\Entity\Company;
use App\Entity\User\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends Voter
{
    public const CREATE = 'CREATE';
    public const VIEW = 'VIEW';
    public const EDIT = 'EDIT';
    public const DELETE = 'DELETE';

    public function __construct(private readonly Security $security)
    {
    }
    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::CREATE, self::VIEW, self::EDIT, self::DELETE])
            && $subject instanceof User;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
        /**
         * @var $subject User
         */
        switch ($attribute) {
            case self::VIEW:
                if ($subject->getCompany()->getUsers()->contains($user)) {
                    return true;
                }
                if ($subject->getUserIdentifier() === $user->getUserIdentifier()) {
                    return true;
                }
                if ($this->security->isGranted('ROLE_SUPER_ADMIN')) {
                    return true;
                }
                break;
        }

        return false;
    }
}
