<?php

namespace App\Handler;

use App\Entity\Result;
use App\Factory\Report\ReportFactory;
use App\Service\SerializerService;

class ReportHandler
{

    public function __construct(private readonly ReportFactory     $factory,
                                private readonly SerializerService $serializer)
    {
    }

    public function build(Result $result, string $format, array $groups): string
    {
        $report = $this->factory->createBuilder()->buildReport($result);
        return $this->serializer->serializeObject($report, $format, $groups);

    }

}