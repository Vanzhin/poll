<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends BaseFixtures
{

    public function loadData(ObjectManager $manager): void
    {

        $this->createMany(Article::class, 10, function (Article $article) use ($manager) {
            $article
                ->setTitle($this->faker->city() . " ". $this->faker->country());


        });
    }
}
