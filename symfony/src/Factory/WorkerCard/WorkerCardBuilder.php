<?php

namespace App\Factory\WorkerCard;

use App\Entity\WorkerCard;


class WorkerCardBuilder
{
    public function __construct()
    {
    }

    public function buildWorkerCard(array $data = []): ?WorkerCard
    {
        $workerCard = new WorkerCard();
        foreach ($data as $key => $item) {
            if ($key === 'firstName' ) {
                $workerCard->setFirstName($item);
                continue;
            };

            if ($key === 'lastName') {
                $workerCard->setLastName($item);
                continue;
            };
            if ($key === 'middleName') {
                $workerCard->setMiddleName($item);
                continue;
            };
            if ($key === 'snils') {
                $workerCard->setSnils($item);
                continue;
            };
            if ($key === 'position') {
                $workerCard->setPosition($item);
                continue;
            };

        }

        return $workerCard;
    }

}