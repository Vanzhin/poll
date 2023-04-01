<?php

namespace App\Service;

use App\Entity\Question;
use App\Entity\Subtitle;
use App\Entity\User;
use App\Entity\Variant;
use App\Factory\Question\QuestionFactory;
use App\Factory\Subtitle\SubtitleFactory;
use App\Factory\Variant\VariantFactory;
use App\Traits\ImageHandle;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;

class QuestionService
{

    use ImageHandle;

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly FileUploader           $questionImageUploader,
        private readonly FileUploader           $variantImageUploader,
        private readonly VariantService         $variantService,
        private readonly ValidationService      $validation,
        private readonly QuestionFactory        $questionFactory,
        private readonly VariantFactory         $variantFactory,
        private readonly SubtitleFactory        $subtitleFactory
    )
    {
    }

    public function delete(Question $question): void
    {
        $this->em->remove($question);
        $this->em->flush();
    }

    public function imageAttach(Question $question, UploadedFile $image = null): Question
    {
        if ($image) {
            $question->setImage($this->questionImageUploader->uploadImage($image, $question->getImage()));
            $this->em->persist($question);
            $this->em->flush();
        };
        return $question;

    }

    public function saveResponse(Question $question, UploadedFile|bool|null $image): array
    {
        try {
            if ($question->getId()) {
                $message = 'Вопрос обновлен';
            } else {
                $message = 'Вопрос создан';

            }
            $this->imageUpdate($question, $this->questionImageUploader, $this->em, $image);
            $response = [
                'message' => $message,
                'questionId' => $question->getId()
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

    public function saveWithVariant(Question $question, ?array $variantData, UploadedFile $questionImage = null, array $variantImages = []): Question
    {
        $this->imageUpdate($question, $this->questionImageUploader, $this->em, $questionImage);

        $this->em->persist($question);
        $this->em->flush();

        foreach ($variantData as $key => $variantItem) {
            $image = array_key_exists($key, $variantImages) ? $variantImages[$key] : false;
            $variantItem['questionId'] = $question->getId();
            $variant = $this->variantFactory->createBuilder()->buildVariant($variantItem ?? []);
            $this->variantService->imageUpdate($variant, $this->variantImageUploader, $this->em, $image);
            $this->em->persist($variant);
            $this->em->flush();

        }

        return $question;
    }

    public function saveWithVariantIfValid(Question $question, array $data, UploadedFile|bool|null $questionImage = null, array $variantImages = null, array $subtitleImages = null): array
    {
        if (!$question->getId()) {
            $message = 'Вопрос создан';
        } else {
            $message = 'Вопрос обновлен';
        }

        $questionErrors = $this->validation->entityWithImageValidate($question, $questionImage instanceof UploadedFile ? $questionImage : null);
        $errors = $questionErrors;
        $this->em->beginTransaction();
        $this->em->persist($question);
        $this->em->flush();
        $variants = [];
        $hasCorrectVariant = [];
        static $i = 0;
        foreach ($data['variant'] ?? [] as $key => $variantData) {
            $variantData['questionId'] = $question->getId();
            if (!is_null($question->getType()) && $question->getType()->getTitle() === 'order') {
                $variantData['correct'] = $i++;
            }
            $variant = $this->variantFactory->createBuilder()->buildVariant($variantData, $this->em->find(Variant::class, $key));
            if ($variant->getCorrect()) {
                $hasCorrectVariant[] = $variant->getId();
            }
            if (!is_null($variantImages) && isset($variantImages[$key])) {
                $image = $variantImages[$key];
            } else {
                $image = null;
            }

            if ($this->validation->entityWithImageValidate($variant, $image)) {
                $errors[] = implode(',', $this->validation->entityWithImageValidate($variant, $image));

            } else {
                $variants[$key] = $variant;
                $this->em->persist($variant);
                $this->em->flush();
            }
        }
        foreach ($question->getVariant() as $variant) {
            if (!key_exists($variant->getId(), $variants)) {
                $this->em->remove($variant);
                $this->em->flush();
            };
        }

        $subtitles = [];

        if (!is_null($question->getType()) && $question->getType()->getTitle() === 'conformity') {
            foreach ($data['subTitle'] ?? [] as $key => $subtitleData) {
                $subtitle = $this->subtitleFactory->createBuilder()->buildSubtitle($subtitleData['title'], $question, isset($subtitleData['variant']) ? $variants[$subtitleData['variant']] : null, $this->em->find(Subtitle::class, $key));
                if (!is_null($subtitleImages) && isset($subtitleImages[$key])) {
                    $image = $subtitleImages[$key];
                } else {
                    $image = null;
                }

                if ($this->validation->entityWithImageValidate($subtitle, $image)) {
                    $errors[] = implode(',', $this->validation->entityWithImageValidate($subtitle, $image));

                } else {
                    $subtitles[$key] = $subtitle;

                    $this->em->persist($subtitle);
                    $this->em->flush();
                }
            }
        }
        foreach ($question->getSubtitles() as $subtitle) {
            if (!key_exists($subtitle->getId(), $subtitles)) {
                $this->em->remove($subtitle);
                $this->em->flush();
            };
        }

        if (count($hasCorrectVariant) < 1 && !in_array($question->getType()->getTitle(), ['input_one', 'conformity'])) {
            $errors[] = 'Необходимо выбрать хотя бы один верный ответ';
        }
        if (count($hasCorrectVariant) > 1 && $question->getType()->getTitle() === 'radio') {
            $errors[] = 'Необходимо выбрать только один верный ответ';
        }


        if (!is_null($errors)) {
            $this->em->rollback();
            return [
                'message' => 'Ошибка при вводе данных',
                'error' => $errors
            ];

        } else {
            try {
                $this->imageUpdate($question, $this->questionImageUploader, $this->em, $questionImage);
                foreach ($variants as $key => $variant) {
                    $this->variantService->imageUpdate($variant, $this->variantImageUploader, $this->em, array_key_exists($key, $variantImages) ? $variantImages[$key] : false);
                }
                foreach ($subtitles as $key => $subtitle) {
                    $this->variantService->imageUpdate($subtitle, $this->variantImageUploader, $this->em, array_key_exists($key, $subtitleImages) ? $subtitleImages[$key] : false);
                }
                $response = [
                    'message' => $message,
                    'question' => $question,
                ];
                $this->em->commit();
            } catch (\Exception $e) {
                $response = ['error' => $e->getMessage()];
            } catch (FilesystemException $e) {
                $response = ['error' => $e->getMessage()];
            } finally {
                return $response;
            }
        }
    }

    public function getUploadedQuestionsSummary(array $questions): array
    {
        $info = [];
        $info['message'] = 'Загружено ' . count($questions) . ' вопросов(а)';
        return $info;

    }

    public function switchPublishForAll(array $questionIds, User $user): array
    {
        $response = [];
        foreach ($questionIds as $id) {
            $question = $this->em->find(Question::class, $id);
            if ($question) {
                $this->changePublish($question, $user);
                $this->em->persist($question);
                $response[$question->getPublishedAt()?'published': 'unpublished'][] = $question->getId();
            }
        };
        $this->em->flush();
        return $response;
    }

    public function changePublished(array $questions, User $user): array
    {
        $response = [];
        foreach ($questions as $question) {
                $this->changePublish($question, $user);
                $this->em->persist($question);
                $response[] = $question->getId();
            }
        $this->em->flush();
        return $response;
    }

    private function changePublish(Question $question, User $user): Question
    {
        if (!$question->getPublishedAt()) {
            $question->setPublishedAt(new \DateTime('now'));
        }else{
            $question->setPublishedAt(null);
        }

        return $question->setAuthor($user);

    }

}