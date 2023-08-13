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
                continue;
            };

            if ($key === 'alias') {
                $category->setAlias($item);
                continue;
            };
            if ($key === 'robots') {
                $category->setRobots($item);
                continue;
            };
            if ($key === 'canonical') {
                $category->setCanonical($item);
                continue;
            };
            if ($key === 'descriptionSeo') {
                $category->setDescriptionSeo($item);
                continue;
            };
        }

        if (!$category->getAlias()) {
            $alias = mb_convert_encoding(substr($category->getTitle(), 0, 30), "UTF-8",);
            $category->setAlias($alias);
        }
        return $category;
    }
}