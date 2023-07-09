<?php

namespace App\Security\Voter;

use App\Entity\Company;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CompanyVoter extends Voter
{
    public const EDIT = 'EDIT';
    public const VIEW = 'VIEW';
    public const MANAGE = 'MANAGE';
    public const DELETE = 'DELETE';


    public function __construct(private readonly Security $security)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::MANAGE])
            && $subject instanceof Company;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /**
         * @var $subject Company
         */
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::MANAGE:
                if ($subject->getUsers()->contains($user)) {
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
