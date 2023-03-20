<?php

namespace App\EventListener;

use App\Entity\Variant;
use App\Service\FileUploader;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use League\Flysystem\FilesystemException;

#[AsEntityListener(event: Events::postRemove, method: 'postRemove', entity: Variant::class)]
class VariantImageRemover
{
    public function __construct(private readonly FileUploader $variantImageUploader)
    {
    }

    /**
     * @throws FilesystemException
     */
    public function postRemove(Variant $variant, LifecycleEventArgs $event): void
    {
        // if this listener only applies to certain entity types,
        // add some code to check the entity type as early as possible
        if ($variant->getImage()) {
            $this->variantImageUploader->delete($variant->getImage());
        }
    }


}