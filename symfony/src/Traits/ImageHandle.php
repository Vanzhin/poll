<?php

namespace App\Traits;

use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait ImageHandle
{

    public function imageUpdate(object $entity, FileUploader $imageUploader, EntityManagerInterface $em, File|bool|null $image = null): object
    {
        switch (gettype($image)) {
            case 'boolean':
                $imageUploader->delete($entity->getImage());
                $entity->setImage(null);
                break;
            case 'NULL':
                //nothing to do
                break;
            case 'object':
                $entity->setImage($imageUploader->uploadImage($image, $entity->getImage()));
                break;
        }
        $em->persist($entity);
        $em->flush();
        return $entity;

    }
}