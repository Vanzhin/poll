<?php

namespace App\Handler;

use App\Entity\Organization;
use App\Entity\Result;
use App\Factory\Report\ReportFactory;
use App\Service\SerializerService;
use DateTimeInterface;

class ReportHandler
{

    public function __construct(private readonly ReportFactory     $factory,
                                private readonly SerializerService $serializer)
    {
    }

    public function build(Result $result, Organization $organization, string $format, array $groups): string
    {
        $report = $this->factory->createBuilder()->buildReport($result, $organization);
        $report->setReport([
            'RegistryRecord' => [
                'Worker' => [
                    'LastName' => $report->getUser()->getWorkerCard()->getLastName(),
                    'FirstName' => $report->getUser()->getWorkerCard()->getFirstName(),
                    'MiddleName' => $report->getUser()->getWorkerCard()->getMiddleName(),
                    'Snils' => $report->getUser()->getWorkerCard()->getSnils(),
                    'Position' => $report->getUser()->getWorkerCard()->getPosition(),
                    'EmployerInn' => $report->getUser()->getWorkerCard()->getOrganization()->getInn(),
                    'EmployerTitle' => $report->getUser()->getWorkerCard()->getOrganization()->getTitle()
                ],
                'Organization' => [
                    'Inn' => $report->getOrganization()->getInn(),
                    'Title' => $report->getOrganization()->getTitle()
                ],
                "Test" => [
                    "@isPassed" => "true",
                    "@learnProgramId" => $result->getTest()->getMinTrudTest()->getOriginalId(),
                    'Date' => $result->getCreatedAt()->format(DateTimeInterface::ATOM),
                    'ProtocolNumber' => $result->getId(),
                    'LearnProgramTitle' => $result->getTest()->getMinTrudTest()->getTitle()

                ]
            ]

        ]);
        return $this->serializer->serializeObject($report->getReport(), $format, $groups);

    }

}