<?php

namespace App\Factory\Report;

use App\Entity\Report;
use App\Entity\Result;
use Doctrine\ORM\EntityManagerInterface;

class ReportBuilder
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function buildReport(Result $result): Report
    {
        $report = new Report();
        $report->setId(rand());
        $report->setResult($result);
        $report->setCreatedAt(new \DateTime('now'));


        return $report;
    }


}