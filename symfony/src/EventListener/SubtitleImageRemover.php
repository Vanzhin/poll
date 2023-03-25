<?php

namespace App\EventListener;

use App\Entity\Subtitle;
use App\Service\FileUploader;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use League\Flysystem\FilesystemException;

#[AsEntityListener(event: Events::postRemove, method: 'postRemove', entity: Subtitle::class)]
class SubtitleImageRemover
{
    public function __construct(private readonly FileUploader $variantImageUploader)
    {
    }

    /**
     * @throws FilesystemException
     */
    public function postRemove(Subtitle $subtitle, LifecycleEventArgs $event): void
    {
        // if this listener only applies to certain entity types,
        // add some code to check the entity type as early as possible
        if ($subtitle->getImage()) {
            $this->variantImageUploader->delete($subtitle->getImage());
        }
    }


}