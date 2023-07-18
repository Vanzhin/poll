<?php

namespace App\Service;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

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

    public function getAllowedAliasesToAssign(): array
    {
        $roles = array_diff($this->roleHierarchy->getReachableRoleNames($this->security->getUser()->getRoles()), $this->security->getUser()->getRoles());
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
                throw new \Exception(sprintf('Роли \'%s\' не существует', $role));
            }
        }
        return $roles;
    }
}