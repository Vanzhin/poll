<?php

namespace App\Service;

use App\Entity\Section;
use App\Entity\Test;
use Doctrine\ORM\EntityManagerInterface;

class SectionService
{

    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function createIfNotExist(string $title, ?Test $test = null): Section
    {
        $section = $this->em->getRepository(Section::class)->findOneBy(['title' => $title]);
        if (!$section) {
            $section = new Section();
            $section->setTitle($title)->setTest($test);

            if ($test) {
                $section->setTest($test);
            }
            $this->em->persist($section);
            $this->em->flush();
        };
        return $section;
    }
}