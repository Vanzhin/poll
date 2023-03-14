<?php

namespace App\Traits;

use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait EntityWithImage
{

    public function delete(object $entity, FileUploader $imageUploader, EntityManagerInterface $em): void
    {

        $imageUploader->delete($entity->getImage());
        $em->remove($entity);
        $em->flush();
    }

    public function imageDelete(object $entity, FileUploader $imageUploader, EntityManagerInterface $em): void
    {

        $imageUploader->delete($entity->getImage());
        $entity->setImage(null);
        $em->persist($entity);
        $em->flush();
    }

    public function imageUpdate(object $entity, FileUploader $imageUploader, EntityManagerInterface $em, UploadedFile $image = null): object
    {

        if ($image) {
            $entity->setImage($imageUploader->uploadImage($image, $entity->getImage()));
        }else{
            $imageUploader->delete($entity->getImage());
            $entity->setImage(null);
        }
        $em->persist($entity);
        $em->flush();
        return $entity;

    }
}