<?php

namespace App\Security\Voter;

use App\Entity\Group;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class GroupVoter extends Voter
{
    public const VIEW = 'VIEW';
    public const MANAGE = 'MANAGE';


    public function __construct(private readonly Security $security)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::MANAGE, self::VIEW])
            && $subject instanceof Group;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /**
         * @var $subject Group
         */
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {

            case self::VIEW:
                if ($user->getGroups()->contains($subject)) {
                    return true;
                }
                if ($this->security->isGranted('ROLE_TUTOR') && $subject->getCompany() === $user->getCompany()) {
                    return true;
                }
                if ($this->security->isGranted('ROLE_SUPER_ADMIN')) {
                    return true;
                }
                break;
            case self::MANAGE:
                if ($this->security->isGranted('ROLE_SUPER_ADMIN')) {
                    return true;
                }
                if ($this->security->isGranted('ROLE_ADMIN') && $subject->getCompany() === $user->getCompany()) {
                    return true;
                }
                if ($this->security->isGranted('ROLE_TUTOR') && $subject->getOwner()->getUserIdentifier() === $user->getUserIdentifier()) {
                    return true;
                }
                break;
        }

        return false;
    }
}