<?php

namespace App\Controller\Api\Admin\Protocol\Action;

use App\Controller\Api\Admin\Protocol\ProtocolController;
use App\Controller\Api\BaseAction\NewBaseAction;
use App\Entity\Protocol\Protocol;
use App\Entity\Result;
use App\Entity\Test;
use App\Entity\User\User;
use App\Factory\Protocol\ProtocolFactory;
use App\Mapper\ProtocolMapper;
use App\Mapper\vo\UserList;
use App\Repository\Interfaces\GroupRepositoryInterface;
use App\Response\AppException;
use App\Security\Voter\GroupVoter;
use App\Security\Voter\ProtocolVoter;
use App\Service\FileHandler;
use App\Service\SerializerService;
use App\Service\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class MassCreationAction extends NewBaseAction
{
    public function __construct(
        SerializerService                         $serializer,
        private readonly EntityManagerInterface   $entityManager,
        private readonly ValidationService        $validator,
        private readonly ProtocolFactory          $factory,
        private readonly Security                 $security,
        private readonly GroupRepositoryInterface $groupRepository,
        private readonly FileHandler              $fileHandler,
        private readonly ProtocolMapper           $mapper,
    )
    {
        parent::__construct($serializer);
    }

    public function run(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $errors = $this->validator->dataValidate($data, $this->mapper->getValidationCollectionMassProtocol());
        if ($errors) {
            throw new AppException(implode(', ', $errors));

        }
        $massCreateProtocol = $this->mapper->buildMassCreateProtocol($data);
// протокол формируется для тестов группы
        $group = $this->groupRepository->getById($massCreateProtocol->getGroupId());
        if (!$group) {
            throw new AppException('Группа не обнаружена.');

        };
        if ($group->getAvailableTests()->filter(fn(Test $test) => ($test->getId() === (int)$massCreateProtocol->getTestId()))->isEmpty()) {
            throw new AppException('Для этого теста/ группы протокол не может быть сформирован');
        };
        if (!in_array($massCreateProtocol->getSettings()->getTemplate(), $this->fileHandler->getFilesList(ProtocolController::TEMPLATE_PATH))) {
            throw new AppException(
                sprintf('Шаблон с названием \'%s\' не найден.', $massCreateProtocol->getSettings()->getTemplate()));
        }

        $users = $group->getParticipants();
        if ($massCreateProtocol->getSettings()->isIgnoreFailedUsers()) {
            //        фильтруем, если нужны только пользователи с положительными результатами
            $filteredUsers = $users
                ->filter(fn(User $user) => ($user->getResults()->filter(fn(Result $result) => ($result->getTest()->getId() === (int)$massCreateProtocol->getTestId() && $result->isPass()))->count() > 0));
        } else {
            //        фильтруем пользователей, у которых есть результаты по тесту
            $filteredUsers = $users->filter(fn(User $user) => ($user->getResults()->filter(fn(Result $result) => ($result->getTest()->getId() === (int)$massCreateProtocol->getTestId()))->count() > 0));
        }
        $created = [];
        if ($massCreateProtocol->getSettings()->isForEach()) {
//            каждый протокол должен иметь уникальный номер
            $i = 1;
            foreach ($filteredUsers as $user) {
                $createProtocol = $this->mapper->buildCreateProtocolFromMass($massCreateProtocol, new UserList($user));
                $protocol = $this->factory->createBuilder()->build($createProtocol);
                $protocol->setNumber($protocol->getNumber() . '-' . $i++);
                $this->checkPermission($protocol);
                $this->entityManager->persist($protocol);
                $created[] = $protocol;
            }
        } else {
            $createProtocol = $this->mapper->buildCreateProtocolFromMass($massCreateProtocol, new UserList(...$filteredUsers->toArray()));
            $protocol = $this->factory->createBuilder()->build($createProtocol);
            $this->checkPermission($protocol);
            $this->entityManager->persist($protocol);
            $created[] = $protocol;

        }

        $this->entityManager->flush();

        return $this->successResponse($created, ['admin_protocol'], 'Протокол(ы) созданы.');
    }

    private function checkPermission(Protocol $protocol): void
    {
        if ($errors = $this->validator->validate($protocol)) {
            throw new AppException(implode(', ', $errors));
        }
        if (!$this->security->isGranted(ProtocolVoter::CREATE, $protocol)) {
            throw new AccessDeniedException(
                sprintf('Вам не разрешено создавать протокол/ протокол для группы с идентификатором \'%s\', комиссией с идентификатором \'%s\'.', $protocol->getGroups()->getId(), $protocol->getCommission()->getId()));
        };
        if (!$this->security->isGranted(GroupVoter::VIEW, $protocol->getGroups())) {
            throw new AppException('У Вас нет доступа к этой группе.');
        };
    }
}