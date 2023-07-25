<?php

namespace App\Service;

use App\Response\AppException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class RoleService
{
    public static array $availableRoleAliases = [
        'ROLE_SUPER_ADMIN' => 'Администратор сайта',
        'ROLE_ADMIN' => 'Администратор',
        'ROLE_USER' => 'Пользователь',
        'ROLE_TUTOR' => 'Наставник',
    ];

    public function __construct(
        private readonly RoleHierarchyInterface $roleHierarchy,
        private readonly Security               $security,
    )
    {
    }

    public function getAllowedAliasesToAssign(UserInterface $user = null): array
    {
        if (!$user) {
            $user = $this->security->getUser();
        }
        $roles = array_diff($this->roleHierarchy->getReachableRoleNames($this->security->getUser()->getRoles()), $user->getRoles());
        $aliases = [self::$availableRoleAliases['ROLE_USER']];
        foreach ($roles as $role) {
            if (isset(self::$availableRoleAliases[$role])) {
                $aliases[] = self::$availableRoleAliases[$role];
            }
        }
        return $aliases;
    }

    public function check(array $roles): bool
    {
        if (count(array_intersect($this->getAllowedAliasesToAssign(), $roles)) !== count($roles)) {
            return false;
        };
        return true;
    }

    public function getRoles(array $aliases): array
    {
        $availableRoles = array_flip(RoleService::$availableRoleAliases);
        $roles = [];
        foreach ($aliases as $role) {
            if (isset($availableRoles[$role])) {
                $roles[] = $availableRoles[$role];
            } else {
                throw new AppException(sprintf('Роли \'%s\' не существует', $role));
            }
        }
        return $roles;
    }

    public function getAliases(array $roles): array
    {
        $aliases = [];
        foreach ($roles as $role) {
            if (isset(self::$availableRoleAliases[$role])) {
                $aliases[] = self::$availableRoleAliases[$role];
            } else {
                throw new AppException(sprintf('Роли \'%s\' не существует', $role));
            }
        }
        return $aliases;
    }
}