<?php

declare(strict_types=1);

namespace App\Factory\Protocol;

use App\Entity\Commission\Commission;
use App\Entity\Group;
use App\Entity\Protocol\Protocol;
use App\Entity\Test;
use App\Mapper\vo\CreateProtocol;
use App\Response\AppException;
use Doctrine\ORM\EntityManagerInterface;

class ProtocolBuilder
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function build(CreateProtocol $createProtocol, Protocol $protocol = null): Protocol
    {
        if (!$protocol) {
            $protocol = new Protocol();
        }
        $commission = $this->em->getRepository(Commission::class)->findOneBy(['id' => (int)$createProtocol->getCommissionId()]);
        if (!$commission) {
            throw new AppException(sprintf('Комиссия с идентификатором \'%s\' не найдена.', $createProtocol->getCommissionId()));
        }
        $test = $this->em->getRepository(Test::class)->findOneBy(['id' => (int)$createProtocol->getTestId()]);
        if (!$test) {
            throw new AppException(sprintf('Тест с идентификатором \'%s\' не найден.', $createProtocol->getTestId()));
        }
        $group = $this->em->getRepository(Group::class)->findOneBy(['id' => (int)$createProtocol->getGroupId()]);
        if (!$group) {
            throw new AppException(sprintf('Группа с идентификатором \'%s\' не найдена.', $createProtocol->getGroupId()));
        }

        $protocol
            ->setCommission($commission)
            ->setTest($test)
            ->setGroups($group)
            ->setOrderNumber($createProtocol->getOrderNumber())
            ->setOrderDate($createProtocol->getOrderDate())
            ->setCheckReason($createProtocol->getCheckReason())
            ->setNumber($createProtocol->getNumber())
            ->setRegNumber($createProtocol->getRegNumber())
            ->setTemplate($createProtocol->getTemplate());

        foreach ($protocol->getUser() as $user) {
            $protocol->removeUser($user);

        }
        foreach ($createProtocol->getUsers()->getList() as $user) {
            $protocol->addUser($user);
        }
        return $protocol;
    }
}