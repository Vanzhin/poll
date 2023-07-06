<?php

namespace App\Factory\Company;

use App\Entity\Category;
use App\Entity\Company;
use Doctrine\ORM\EntityManagerInterface;

class CompanyBuilder
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function buildCompany(array $data, Company $company = null): Company
    {
        if (!$company) {
            $company = new Company($data['user']);
        }
        foreach ($data as $key => $item) {
            if ($key === 'title') {
                $company->setTitle($item);
                continue;
            };
            if ($key === 'tin') {
                $company->setTin($item);
                continue;
            };
        }

        return $company;
    }
}