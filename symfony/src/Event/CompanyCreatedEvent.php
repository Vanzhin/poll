<?php

namespace App\Event;

use App\Entity\Company;
use Symfony\Contracts\EventDispatcher\Event;

class CompanyCreatedEvent extends Event
{
    public const NAME = 'company.created';

    public function __construct(private readonly Company $company)
    {
    }

    /**
     * @return Company
     */
    public function getCompany(): Company
    {
        return $this->company;
    }
}