<?php

namespace App\DataFixtures;

use App\Entity\Type;
use Doctrine\Persistence\ObjectManager;

class TypeFixtures extends BaseFixtures
{
    private static array $titles = [
        'radio',
        'checkbox'
    ];


    function loadData(ObjectManager $manager)
    {
        foreach (self::$titles as $title){
            $this->create(Type::class, function (Type $type) use ($manager, $title) {
                $type
                    ->setTitle($title);
            });
        }
        $this->manager->flush();

    }
}
