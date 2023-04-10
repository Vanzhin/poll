<?php

namespace App\Factory\Organization;

use App\Entity\Organization;
use Doctrine\ORM\EntityManagerInterface;

class OrganizationBuilder
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function buildOrganization(array $data, Organization $organization = null): Organization
    {
        if (!$organization) {
            $organization = new Organization();
        }
        foreach ($data as $key => $item) {
            if ($key === 'title') {
                $organization->setTitle($item);
                continue;
            };
            if ($key === 'inn') {
                $organization->setInn($item);
                continue;

            };
        }
        return $organization;
    }

}