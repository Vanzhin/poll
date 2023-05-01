<?php

namespace App\Factory\Category;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;

class CategoryBuilder
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function buildCategory(array $data, Category $category = null): Category
    {
        if (!$category) {
            $category = new Category();
        }
        foreach ($data as $key => $item) {
            if ($key === 'title') {
                $category->setTitle($item);
                continue;
            };
            if ($key === 'description') {
                $category->setDescription($item);
                continue;

            };
            if ($key === 'parentId') {
                $category->setParent($this->em->find(Category::class, $item));

            };

            if ($key === 'alias') {
                $category->setAlias($item);

            };

        }

        if (!$category->getAlias()) {
            $category->setAlias(substr($category->getTitle(), 0, 30));
        }
        return $category;
    }

}