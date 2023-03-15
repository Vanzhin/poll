<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Question;
use App\Entity\Test;
use App\Traits\EntityWithImage;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CategoryService
{
    public function __construct(private readonly EntityManagerInterface $em, private readonly FileUploader $categoryImageUploader)
    {
    }
    use EntityWithImage;

    public function make(Category $category, array $data): Category
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

        return $category;
    }

    public function saveResponse(Category $category, UploadedFile|bool|null $image): array
    {
        try {
            if ($category->getId()) {
                $message = 'Раздел обновлен';
            } else {
                $message = 'Раздел создан';

            }
            $this->imageUpdate($category, $this->categoryImageUploader, $this->em, $image);

            $response = [
                'message' => $message,
                'categoryId' => $category->getId()
            ];
            $status = 200;
        } catch (\Exception $e) {
            $response = ['error' => $e->getMessage()];
            $status = 501;
        } catch (FilesystemException $e) {
            $response = ['error' => $e->getMessage()];
            $status = 501;
        } finally {
            return ['response' => $response, 'status' => $status];
        }

    }

//    public function delete(Category $category): void
//    {
//
//        $this->categoryImageUploader->delete($category->getImage());
//        $this->em->remove($category);
//        $this->em->flush();
//    }
//
//    public function imageDelete(Category $category): void
//    {
//
//        $this->categoryImageUploader->delete($category->getImage());
//        $category->setImage(null);
//        $this->em->persist($category);
//        $this->em->flush();
//    }
}