<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Test;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TestService
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function make(Test $test, array $data): Test
    {
        foreach ($data as $key => $item) {
            if ($key === 'title') {
                $test->setTitle($item);
                continue;
            };
            if ($key === 'description') {
                $test->setDescription($item);
                continue;

            };
            if ($key === 'category' && $this->em->find(Category::class, $item)) {
                $test->setCategory($this->em->find(Category::class, $item));

            };

        }

        return $test;
    }
}