<?php

namespace App\Service;

use App\Entity\Question;
use App\Entity\Variant;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemException;
use Symfony\Component\HttpFoundation\FileBag;

class VariantService
{
    public function __construct(private readonly EntityManagerInterface $em, private readonly FileUploader $variantImageUploader)
    {
    }

    /**
     * @throws FilesystemException
     */
    public function save(Variant $variant, array $data, Question $question = null, FileBag $files = null): Variant
    {
        foreach ($data as $key => $item) {
            if ($key === 'title') {
                $variant->setTitle($item);
                continue;
            };

        }
        if (isset($data['weight'])) {
            $variant->setWeight($data['weight']);
        } else {
            $variant->setWeight(1);
        }

        if ($question) {
            $variant->setQuestion($question);
        }
        if($files && $files->keys()){
            foreach ($files->keys() as $key){
                if ($key ==='variant') {
                    foreach ($files->get($key)['img'] ?? [] as $image) {
                        $variant->setImage($this->variantImageUploader->uploadImage($image));
                    };
                }
            }
        }



        $this->em->persist($variant);
        $this->em->flush();
        return $variant;
    }
}