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
                    'LastName' => $report->getUser()->getLastName(),
                    'FirstName' => $report->getUser()->getFirstName(),
                    'MiddleName' => $report->getUser()->getMiddleName(),
                    'Snils' => $report->getUser()->getSnils(),
                    'Position' => $report->getUser()->getPosition(),
                    'EmployerInn' => $report->getUser()->getOrganization()->getInn(),
                    'EmployerTitle' => $report->getUser()->getOrganization()->getTitle()
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