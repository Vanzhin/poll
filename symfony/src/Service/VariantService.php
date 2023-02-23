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
            if ($key === 'correct') {
                switch ($question->getType()->getTitle()) {
                    case 'radio':
                        if ($item === 'true') {
                            $question->setAnswer([$variant->getId()]);
                        }
                        break;
                    case 'order':
                    case 'input_one':

                        break;
                    case 'conformity':

                        break;
                    case 'checkbox':
                    case 'checkbox_picture':

                        break;
//            case 'input_many':
//            case 'blank':

                }

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

        $this->em->persist($variant);
        $this->em->flush();
        return $variant;
    }

    public function delete(Variant $variant): void
    {
        $answers = $variant->getQuestion()->getAnswer();
        $keys = array_keys($answers, $variant->getId());
        foreach ($keys as $key) {
            unset($answers[$key]);
        }

        $question = $variant->getQuestion()->setAnswer($answers);
        $this->variantImageUploader->delete($variant->getImage());
        $this->em->persist($question);
        $this->em->remove($variant);
        $this->em->flush();
    }
}