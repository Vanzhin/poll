<?php

namespace App\Factory\Report;

use App\Entity\Organization;
use App\Entity\Report;
use App\Entity\Result;
use Doctrine\ORM\EntityManagerInterface;

class ReportBuilder
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function buildReport(Result $result, Organization $organization): Report
    {
        $report = new Report();
        $report->setId(rand());
        $report->setResult($result);
        $report->setUser($result->getUser());
        $report->setOrganization($organization);
        $report->setCreatedAt(new \DateTime('now'));


        return $report;
    }


}