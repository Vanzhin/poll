<?php

namespace App\DataFixtures;

use App\Entity\Type;
use Doctrine\Persistence\ObjectManager;

class TypeFixtures extends BaseFixtures
{
    private static array $titles = [
        'radio',
        'checkbox',
        'input_one',
        'input_many',
        'conformity',
        'order',
        'checkbox_picture',
        'textarea',
        'blank',
    ];


    function loadData(ObjectManager $manager)
    {
        foreach (static::$titles as $i => $title) {
            $entity = $this->create(Type::class, function (Type $type) use ($manager, $title) {
                $type
                    ->setTitle($title);
            });
            $this->addReference(Type::class . "|$i", $entity);
        }

        $this->manager->flush();

    }
}
