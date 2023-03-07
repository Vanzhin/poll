<?php

namespace App\Service;

use App\Entity\Question;
use App\Entity\Variant;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class VariantService
{
    public function __construct(private readonly EntityManagerInterface $em, private readonly FileUploader $variantImageUploader)
    {
    }

    public function save(Variant $variant, array $data): Variant
    {
        if (!$variant->getQuestion()) {
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

    public function questionUpdate(Variant $variant): Question
    {
        $question = $variant->getQuestion();
        switch ($question->getType()->getTitle()) {
            case 'radio':
                if ($variant->getIsCorrect()) {
                    $answers = [$variant->getId()];
                    $question->setAnswer($answers);

                } else {
                    if (in_array($variant->getId(), $question->getAnswer())) {
                        $question->setAnswer([]);
                    }

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
                if ($variant->getIsCorrect()) {
                    $answers[] = $variant->getId();
                } else {
                    $answers = array_filter($answers, function ($variantId) use ($variant) {
                        return $variantId !== $variant->getId();
                    });

                }
                $question->setAnswer($answers);

                break;
        }

        return $question;
    }


    public function saveResponse(Variant $variant, ?UploadedFile $image): array
    {
        try {
            if ($variant->getId()) {
                $message = 'Вариант обновлен';
            } else {
                $message = 'Вариант создан';
            }

            if ($image) {
                $variant->setImage($this->variantImageUploader->uploadImage($image, $variant->getImage()));
            };

            $this->em->persist($variant);
            if (!$variant->getId()) {
                $this->em->flush();
            }
            $this->em->persist($this->questionUpdate($variant));
            $this->em->flush();
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