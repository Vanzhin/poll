<?php

namespace App\Controller\Api\User\Action;

use App\Controller\Api\BaseAction\NewBaseAction;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Response\AppException;
use App\Service\Mailer;
use App\Service\SerializerService;

class SendLoginLinkAction extends NewBaseAction
{
    public function __construct(
        SerializerService                        $serializer,
        private readonly UserRepositoryInterface $userRepository,
        private readonly Mailer                  $mailer
    )
    {
        parent::__construct($serializer);
    }

    public function run(array $data)
    {

        $list = $data['list'];
        foreach ($list as $userData) {
            $user = $this->userRepository->findOneByLogin($userData['login'] ?? '');
            if (!$user) {
                throw new AppException(sprintf('Пользователь с логином %s не найден', $userData['login']));
            }

            $unavailableChannels = array_diff($userData['transport'], $user->getProfile()?->getNotificationType() ?? []);
            if (count($unavailableChannels) > 0) {
                throw new AppException(sprintf('Для пользователя %s не доступны следующие каналы: %s', $user->getLogin(), implode(', ', $unavailableChannels)));
            }
            foreach ($userData['transport'] as $notificationType) {
                switch ($notificationType) {
                    case 'email':
                        $this->mailer->sendLoginLinkEmail($user);
                        break;
                    case 'phone':
                        break;
                }
            }
        }
        return $this->successResponse([]);
    }
}