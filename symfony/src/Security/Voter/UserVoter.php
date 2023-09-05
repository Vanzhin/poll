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
            case self::EDIT:
            case self::CREATE:
                // могу, если супер админ
                if ($this->security->isGranted('ROLE_SUPER_ADMIN')) {
                    return true;
                }
                // не могу, если это админ и не я
                if ($subject->getUserIdentifier() !== $user->getUserIdentifier() && in_array('ROLE_ADMIN', $subject->getRoles())) {
                    return false;
                }
                // могу, если пользователь из моей компании
                if ($this->security->isGranted('ROLE_ADMIN') && $subject->getCompany()?->getUsers()->contains($user)) {
                    return true;
                }
                // могу, если это я
                if ($subject->getUserIdentifier() === $user->getUserIdentifier()) {
                    return true;
                }
                break;
            case self::DELETE:
                // не могу, если пользователь супер админ
                if (in_array('ROLE_SUPER_ADMIN', $subject->getRoles())) {
                    return false;
                }
                // могу, если супер админ
                if ($this->security->isGranted('ROLE_SUPER_ADMIN')) {
                    return true;
                }
                // не могу, если пользователь админ
                if (in_array('ROLE_ADMIN', $subject->getRoles())) {
                    return false;
                }
                // могу, если я админ и пользователь из моей компании
                if ($this->security->isGranted('ROLE_ADMIN') && $subject->getCompany()?->getUsers()->contains($user)) {
                    return true;
                }

                break;
        }

        return false;
    }
}