<?php

namespace App\Traits;

use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait ImageHandle
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

    public function imageUpdate(object $entity, FileUploader $imageUploader, EntityManagerInterface $em, UploadedFile|bool|null $image = null): object
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