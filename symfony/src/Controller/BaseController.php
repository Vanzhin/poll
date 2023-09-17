<?php

namespace App\Controller;

use App\Entity\Group;
use App\Entity\User\User;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Security\Voter\UserVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;

class BaseController extends AbstractController
{
    public function __construct(private readonly UserRepositoryInterface $repository)
    {
    }

    protected function getStats(UserInterface $user): array
    {
        $users = [$user];
        $company = $user->getCompany();
        if (!$company) {
            return [];
        }
        if ($this->isGranted(UserVoter::STATISTIC, $user)) {
//            не должен видеть других админов, если они будут
            $companyUsers = $this->repository->findUsersByCompany($company);
//            должен видеть пользователей только там, где владелец группы
            if (in_array('ROLE_TUTOR', $user->getRoles())) {
                $companyUsers = array_filter($companyUsers, function (User $companyUser) use ($user) {
                    return !$companyUser->getGroups()->filter(fn(Group $group) => $group->getOwner()->getUserIdentifier() === $user->getUserIdentifier())->isEmpty();
                });
            }

            $users = array_merge($users, $companyUsers);
        }
        return array_unique(array_map(fn(User $user) => (string)$user->getId(), $users));
    }

}