<?php

declare(strict_types=1);

namespace App\Factory\Protocol;

use App\Entity\Commission\Commission;
use App\Entity\Group;
use App\Entity\Protocol\Protocol;
use App\Entity\Protocol\vo\ProtocolSettings;
use App\Entity\Test;
use App\Response\AppException;
use Doctrine\ORM\EntityManagerInterface;

class ProtocolBuilder
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function build(array $data, Protocol $protocol = null): Protocol
    {
        if (!$protocol) {
            $protocol = new Protocol();
        }

        foreach ($data as $key => $item) {
            if ($key === 'order_number') {
                $protocol->setOrderNumber($item);
                continue;
            };
            if ($key === 'commission_id') {
                $commission = $this->em->getRepository(Commission::class)->findOneBy(['id' => (int)$item]);
                if (!$commission) {
                    throw new AppException(sprintf('Комиссия с идентификатором \'%s\' не найдена.', $item));
                }
                $protocol->setCommission($commission);
                continue;
            };
            if ($key === 'group_id') {
                $group = $this->em->getRepository(Group::class)->findOneBy(['id' => (int)$item]);
                if (!$group) {
                    throw new AppException(sprintf('Группа с идентификатором \'%s\' не найдена.', $item));
                }
                $protocol->setGroups($group);
                continue;
            };
            if ($key === 'order_date') {
                $date = \DateTimeImmutable::createFromFormat('Y-m-d', $item);
                if (!$date instanceof \DateTimeImmutable) {
                    throw new AppException('Не верный формат даты. Формат ГГГГ-ММ-ДД');

                }
                $protocol->setOrderDate(\DateTimeImmutable::createFromFormat('Y-m-d', $item));
                continue;
            }
            if ($key === 'check_reason') {
                $protocol->setCheckReason($item);
                continue;
            }
            if ($key === 'number') {
                $protocol->setNumber($item);
                continue;
            }
            if ($key === 'test_id') {
                $test = $this->em->getRepository(Test::class)->findOneBy(['id' => (int)$item]);
                if (!$test) {
                    throw new AppException(sprintf('Тест с идентификатором \'%s\' не найден.', $item));
                }
                $protocol->setTest($test);
                continue;
            }
            if ($key === 'settings') {
                $protocol->setSettings($this->buildSettings($item));
                continue;
            }
        }

        return $protocol;
    }

    private function buildSettings(array $items): ProtocolSettings
    {
        try {
            return new ProtocolSettings(...$items);

        } catch (\Exception|\Error $e) {
            throw new AppException(sprintf('Настройки протокола не созданы. Обязательные поля: %s',
                implode(', ', array_map(fn($property) => ($property->getName()), (new \ReflectionClass(ProtocolSettings::class))->getProperties()))));
        }
    }
}