<?php

namespace App\Service;

use App\Entity\Question;
use App\Entity\Variant;
use App\Traits\ImageHandle;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class VariantService
{
    use ImageHandle;

    public function __construct(private readonly EntityManagerInterface $em, private readonly FileUploader $variantImageUploader)
    {
    }

    public function make(Variant $variant, array $data): Variant
    {
        if (!$variant->getQuestion() && isset($data['questionId'])) {
            $question = $this->em->find(Question::class, $data['questionId']);
        } else {
            $question = $variant->getQuestion();
        }
        foreach ($data as $key => $item) {
            if ($key === 'title') {
                $variant->setTitle($item);
                continue;
            };
            if ($key === 'correct' && $item === 'true') {
                $variant->setIsCorrect(true);
                continue;
            };
        }
        if (isset($data['weight'])) {
            $variant->setWeight($data['weight']);
        } else {
            $variant->setWeight(100);
        }
        if ($question) {
            $variant->setQuestion($question);
        }
        return $variant;

    }


    public function imageAttach(Variant $variant, UploadedFile $image = null): void
    {
        if ($image) {
            $variant->setImage($this->variantImageUploader->uploadImage($image, $variant->getImage()));
            $this->em->persist($variant);
            $this->em->flush();
        };

    }

    public function saveResponse(Variant $variant, UploadedFile|bool|null $image): array
    {
        try {
            if ($variant->getId()) {
                $message = 'Вариант обновлен';
            } else {
                $message = 'Вариант создан';
                $this->em->persist($variant);
                $this->em->flush();

            }
            $this->imageUpdate($variant, $this->variantImageUploader, $this->em, $image);

            $response = [
                'message' => $message,
                'variantId' => $variant->getId()
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

    public function delete(Variant $variant): void
    {
//        $answers = array_filter($variant->getQuestion()->getAnswer(), function ($variantId) use ($variant) {
//            return $variantId !== $variant->getId();
//        });
//        $question = $variant->getQuestion()->setAnswer(array_values($answers));
//        $this->variantImageUploader->delete($variant->getImage());
//        $this->em->persist($question);
        $this->em->remove($variant);
        $this->em->flush();
    }
}