<?php

namespace App\Controller\Api\Admin\Protocol\Action;

use App\Controller\Api\BaseAction\NewBaseAction;
use App\Entity\Protocol;
use App\Factory\Protocol\ProtocolFactory;
use App\Repository\Interfaces\GroupRepositoryInterface;
use App\Response\AppException;
use App\Security\Voter\ProtocolVoter;
use App\Service\SerializerService;
use App\Service\ValidationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UpdateAction extends NewBaseAction
{
    public function __construct(
        SerializerService                         $serializer,
        private readonly EntityManagerInterface   $entityManager,
        private readonly ValidationService        $validator,
        private readonly ProtocolFactory          $factory,
        private readonly Security                 $security,
        private readonly GroupRepositoryInterface $groupRepository,
    )
    {
        parent::__construct($serializer);
    }

    public function run(Protocol $protocol, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

// может быть один протокол для группы
        if ($protocol->getGroups()->getId() === (int)$data['group_id']) {
            $groupProtocol = $this->groupRepository->getById((int)$data['group_id'])?->getProtocol();
            if (!is_null($groupProtocol) && $groupProtocol->getId() !== $protocol->getId()) {
                throw new AppException(sprintf('Для группы с идентификатором \'%s\' протокол уже сформирован.', $groupProtocol?->getGroups()->getId()));
            }
        }

        $protocol = $this->factory->createBuilder()->build($data, $protocol);
        if ($errors = $this->validator->validate($protocol)) {
            throw new AppException(implode(', ', $errors));
        }
        if (!$this->security->isGranted(ProtocolVoter::EDIT, $protocol)) {
            throw new AccessDeniedException(
                sprintf('Вам не разрешено создавать протокол/ протокол для группы с идентификатором\'%s\', комиссией с идентификатором\'%s\'.', $protocol->getGroups()->getId(), $protocol->getCommission()->getId()));
        };
        $this->entityManager->persist($protocol);
        $this->entityManager->flush();

        return $this->successResponse($protocol, ['admin_protocol'], 'Протокол обновлен.');
    }
}