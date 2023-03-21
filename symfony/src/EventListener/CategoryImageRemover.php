<?php

namespace App\EventListener;

use App\Entity\Category;
use App\Service\FileUploader;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use League\Flysystem\FilesystemException;

#[AsEntityListener(event: Events::postRemove, method: 'postRemove', entity: Category::class)]
class CategoryImageRemover
{
    public function __construct(private readonly FileUploader $categoryImageUploader)
    {
    }

    /**
     * @throws FilesystemException
     */
    public function postRemove(Category $category, LifecycleEventArgs $event): void
    {
        // if this listener only applies to certain entity types,
        // add some code to check the entity type as early as possible
        if ($category->getImage()) {
            $this->categoryImageUploader->delete($category->getImage());
        }
    }


}