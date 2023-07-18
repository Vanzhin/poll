<?php

namespace App\Factory\User;

use App\Entity\Company;
use App\Entity\Profile;
use App\Entity\User\User;
use App\Entity\User\vo\WorkerCard;
use App\Enum\Role;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserBuilder
{
    public function __construct(private readonly EntityManagerInterface      $em,
                                private readonly UserPasswordHasherInterface $userPasswordHasher,
    )
    {
    }

    public function updateUserRole(array $data, UserInterface $user): UserInterface
    {
        foreach ($data as $key => $item) {
            if ($key === 'role' && is_array($item)) {
                $userRoles = [];
                foreach ($item as $role) {
                    $allRoles = array_column(Role::cases(), 'name');
                    $roleToAttach = Role::tryFrom($role)?->name;
                    if (in_array($roleToAttach, $allRoles)) {
                        $userRoles[] = $roleToAttach;
                    }
                }
                $user->setRoles(array_unique($userRoles));
            }
        }

        return $user;
    }

    public function buildUser(string $email, string $password = null, string $firstName = null): ?User
    {
        $user = new User();
        $user->setEmail($email)->setFirstName($firstName);
        if ($password) {
            $user->setPassword(
                $this->userPasswordHasher->hashPassword(
                    $user,
                    $password
                )
            );
        }
        return $user;
    }

    // создал новый билдер для пользователя компании
    public function buildCompanyUser(array $data, Company $company, User $user = null, Profile $profile = null): User
    {
        if (!$user) {
            $user = new User();
        }
        foreach ($data as $key => $item) {
            if ($key === 'email') {
                $user->setEmail($item);
                continue;
            };
            if ($key === 'login') {
                $user->setLogin($item);
                continue;
            };
            if ($key === 'roles' && is_array($item)) {
                if (($i = array_search('ROLE_USER', $item)) !== false) {
                    unset($item[$i]);
                }
                $user->setRoles($item);
                continue;
            };
            if ($key === 'password') {
                $user->setPassword(
                    $this->userPasswordHasher->hashPassword(
                        $user,
                        $item
                    )
                );
                continue;
            };
            if ($key === 'isActive') {
                $user->setIsActive($item);
                continue;
            };
        }
        $user->setProfile($profile);
        $user->setCompany($company);

        return $user;
    }

    public function updateUser(User $user, array $data): User
    {
        foreach ($data as $key => $item) {
            if ($key === 'firstName') {
                $user->setFirstName($item);
                continue;
            };
            if ($key === 'email') {
                $user->setEmail($item);
                continue;
            };

        }

        return $user;

    }

    public function createWorker(User $user): WorkerCard
    {
        $worker = new WorkerCard();

        return $worker;
    }

    public function updateUserPassword(User $user, string $plainPassword): User
    {
        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                $plainPassword
            )
        );
        return $user;

    }


}