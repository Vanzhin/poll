<?php

namespace App\Mapper;

use App\Mapper\vo\CreateProtocol;
use App\Mapper\vo\MassCreateProtocol;
use App\Mapper\vo\CreateProtocolSettings;
use App\Mapper\vo\UserList;
use Symfony\Component\Validator\Constraints as Assert;


class ProtocolMapper
{
    public function buildMassCreateProtocol(array $data): MassCreateProtocol
    {
        return new MassCreateProtocol(
            $data['commission_id'],
            $data['group_id'],
            $data['order_number'],
            \DateTimeImmutable::createFromFormat('Y-m-d', $data['order_date']),
            $data['check_reason'],
            $data['number'],
            $data['test_id'],
            $data['reg_number'],
            new CreateProtocolSettings(
                $data['settings']['template'],
                $data['settings']['ignore_failed_users'],
                $data['settings']['for_each'],

            )
        );

    }

    public function buildCreateProtocolFromMass(MassCreateProtocol $massCreateProtocol, UserList $users): CreateProtocol
    {
        return new CreateProtocol(
            $massCreateProtocol->getCommissionId(),
            $massCreateProtocol->getGroupId(),
            $massCreateProtocol->getOrderNumber(),
            $massCreateProtocol->getOrderDate(),
            $massCreateProtocol->getCheckReason(),
            $massCreateProtocol->getNumber(),
            $massCreateProtocol->getTestId(),
            $massCreateProtocol->getRegNumber(),
            $massCreateProtocol->getSettings()->getTemplate(),
            $users
        );

    }

    public function buildCreateProtocol(array $data, UserList $users): CreateProtocol
    {
        return new CreateProtocol(
            $data['commission_id'],
            $data['group_id'],
            $data['order_number'],
            \DateTimeImmutable::createFromFormat('Y-m-d', $data['order_date']),
            $data['check_reason'],
            $data['number'],
            $data['test_id'],
            $data['reg_number'],
            $data['template'],
            $users
        );

    }

    public function getValidationCollectionMassProtocol(): Assert\Collection
    {
        return new Assert\Collection([
            'commission_id' => [
                new Assert\NotBlank(),
                new Assert\NotNull(),
                new Assert\Type('digit'),
            ],
            'group_id' => [
                new Assert\NotBlank(),
                new Assert\NotNull(),
                new Assert\Type('digit'),
            ],
            'test_id' => [
                new Assert\NotBlank(),
                new Assert\NotNull(),
                new Assert\Type('digit'),
            ],
            'order_number' => [
                new Assert\NotBlank(),
                new Assert\NotNull(),
                new Assert\Type('string'),
                new Assert\Length(max: 10)
            ],
            'order_date' => [
                new Assert\NotNull(),
                new Assert\DateTime('Y-m-d'),
            ],
            'check_reason' => [
                new Assert\NotBlank(),
                new Assert\NotNull(),
                new Assert\Type('string'),
            ],
            'number' => [
                new Assert\NotBlank(),
                new Assert\NotNull(),
                new Assert\Type('string'),
                new Assert\Length(max: 25)
            ],
            'reg_number' => [
                new Assert\Type('string'),
                new Assert\Length(max: 255)
            ],
            'settings' => new Assert\Collection([
                'template' => [
                    new Assert\NotBlank(),
                    new Assert\NotNull(),
                    new Assert\Type('string'),
                ],
                'ignore_failed_users' => [
                    new Assert\Type('boolean'),
                ],
                'for_each' => [
                    new Assert\Type('boolean'),
                ],

            ])
        ]);
    }

    public function getValidationCollectionProtocol(): Assert\Collection
    {
        return new Assert\Collection([
            'commission_id' => [
                new Assert\NotBlank(),
                new Assert\NotNull(),
                new Assert\Type('digit'),
            ],
            'group_id' => [
                new Assert\NotBlank(),
                new Assert\NotNull(),
                new Assert\Type('digit'),
            ],
            'test_id' => [
                new Assert\NotBlank(),
                new Assert\NotNull(),
                new Assert\Type('digit'),
            ],
            'order_number' => [
                new Assert\NotBlank(),
                new Assert\NotNull(),
                new Assert\Type('string'),
                new Assert\Length(max: 10)
            ],
            'order_date' => [
                new Assert\NotNull(),
                new Assert\DateTime('Y-m-d'),
            ],
            'check_reason' => [
                new Assert\NotBlank(),
                new Assert\NotNull(),
                new Assert\Type('string'),
            ],
            'number' => [
                new Assert\NotBlank(),
                new Assert\NotNull(),
                new Assert\Type('string'),
                new Assert\Length(max: 25)
            ],
            'reg_number' => [
                new Assert\Type('string'),
                new Assert\Length(max: 255)
            ],
            'template' => [
                new Assert\NotBlank(),
                new Assert\NotNull(),
                new Assert\Type('string'),
            ],
            'users' => new Assert\All([
                new Assert\NotBlank(),
                new Assert\NotNull(),
                new Assert\Type('digit'),
            ]),
        ]);
    }
}