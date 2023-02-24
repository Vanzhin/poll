<?php

namespace App\Service;

use App\Entity\Question;
use App\Entity\Variant;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemException;
use Symfony\Component\HttpFoundation\File\File;

class VariantService
{
    public function __construct(private readonly EntityManagerInterface $em, private readonly FileUploader $variantImageUploader)
    {
    }

    /**
     * @throws FilesystemException
     */
    public function save(Variant $variant, array $data, File $image = null): Variant
    {
        $question = $this->em->find(Question::class, $data['questionId']);
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

        if ($image) {
            $variant->setImage($this->variantImageUploader->uploadImage($image, $variant->getImage()));
        };

        if (!$variant->getId()) {
            $this->em->persist($variant);
            $this->em->flush();
        }

        switch ($question->getType()->getTitle()) {
            case 'radio':
                if (isset($data['correct']) && $data['correct'] === 'true') {
                    $answers = [$variant->getId()];
                    $question->setAnswer($answers);

                }

                break;
            case 'order':
//            case 'conformity':

                $answers = $question->getAnswer();
                $answers[] = $variant->getId();
                $question->setAnswer($answers);

                break;
            case 'checkbox':
            case 'checkbox_picture':
                $answers = $question->getAnswer();
                if (isset($data['correct']) && $data['correct'] === 'true') {
                    $answers[] = $variant->getId();
                } else {
                    $answers = array_filter($answers, function ($variantId) use ($variant) {
                        return $variantId !== $variant->getId();
                    });

                }
                $question->setAnswer($answers);

                break;

        }


        $this->em->persist($variant);
        $this->em->flush();
        return $variant;

    }

    public function delete(Variant $variant): void
    {
        $answers = array_filter($variant->getQuestion()->getAnswer(), function ($variantId) use ($variant) {
            return $variantId !== $variant->getId();
        });

        $question = $variant->getQuestion()->setAnswer($answers);
        $this->variantImageUploader->delete($variant->getImage());
        $this->em->persist($question);
        $this->em->remove($variant);
        $this->em->flush();
    }
}