<?php

namespace App\Factory\Commission;

use App\Entity\Commission\Commission;
use App\Entity\Company;
use App\Entity\User\User;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Response\AppException;

class CommissionBuilder
{
    public function __construct(private readonly UserRepositoryInterface $repository)
    {
    }

    public function build(array $data, Company $company = null, Commission $commission = null): Commission
    {
        if (!$commission) {
            $commission = new Commission();
        }
        if ($company) {
            $commission->setCompany($company);
        }
        foreach ($data as $key => $item) {
            if ($key === 'title') {
                $commission->setTitle($item);
                continue;
            };
            if ($key === 'participants') {
//                todo вынести отсюда в проверку выше в экшн наверно, пока не знаю, здесь этого быть не должно
                $users = $this->repository->findAllById(...$item);
                $commission->removeAllParticipants();
                foreach (array_unique($item) as $userId) {
                    if (!$user = current(array_filter($users, fn(User $user) => ($user->getId() === $userId)))) {
                        throw new AppException(sprintf('Пользователь с идентификатором \'%d\' не найден', $userId));
                    }
                    if (!$user->getProfile()) {
                        throw new AppException(sprintf('В комиссии могут участвовать только пользователи с заполненными данными профиля. Пользователь с идентификатором \'%d\' не имеет профиля.', $user->getId()));

                    }
                    $commission->addParticipant($user);
                    if (isset($data['head']) && $userId === $data['head']) {
                        $commission->setHead($user);
                    }
                }
                continue;
            };
        }
        return $commission;
    }
}