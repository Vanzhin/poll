<?php

namespace App\EventListener;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;

#[AsDoctrineListener(event: Events::postRemove, priority: 500, connection: 'default')]
class ImageRemover
{

    public function postRemove(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        // if this listener only applies to certain entity types,
        // add some code to check the entity type as early as possible
//        if ($entity instanceof EntityWithImageInterface) {
//            $entity->getImageUploader()->delete($entity->getImage());
//        }

    }
}