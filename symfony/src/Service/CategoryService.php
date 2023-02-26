<?php

namespace App\Service;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CategoryService
{
    public function __construct(private readonly EntityManagerInterface $em, private readonly FileUploader $categoryImageUploader)
    {
    }

    /**
     * @throws FilesystemException
     */
    public function save(Category $category, array $data, UploadedFile $image = null): Category
    {
        foreach ($data as $key => $item) {
            if ($key === 'title') {
                $category->setTitle($item);
                continue;
            };
            if ($key === 'description') {
                $category->setDescription($item);
                continue;

            };
            if ($key === 'parentId') {
                $category->setParent($this->em->find(Category::class, $item));

            };

        }

        if ($image) {
            $category->setImage($this->categoryImageUploader->uploadImage($image, $category->getImage()));
        };

        $this->em->persist($category);
        $this->em->flush();
        return $category;
    }

    public function delete(Category $category): void
    {

        $this->categoryImageUploader->delete($category->getImage());
        $this->em->remove($category);
        $this->em->flush();
    }
}