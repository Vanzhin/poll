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

    public function saveWithVariantIfValid(Question $question, array $data, UploadedFile|bool|null $questionImage = null, array $variantImages = null, array $subtitleImages = null): array
    {
        if (!$question->getId()) {
            $message = 'Вопрос создан';
        } else {
            $message = 'Вопрос обновлен';
        }
//        todo убрать костыль
        if (!$question->getId()) {
            foreach ($data['variant'] as $key => $variantData) {
                if (key_exists($key, $variantImages)) {
                    $data['variant'][$key]['image'] = $key;

                }
            };
        }
        $response = $this->createOrUpdateQuestionIfValid($question, $data, $questionImage, $variantImages, $subtitleImages);

        if (!is_null($response['error'])) {
            return [
                'message' => 'Ошибка при вводе данных',
                'error' => $response['error']
            ];

        } else {
            try {
                $question = $this->saveWithVariants($question, $response['variants'], $response['subtitles'], $questionImage, $variantImages, $subtitleImages);
                $response = [
                    'message' => $message,
                    'question' => $question,
                ];
            } catch (\Exception $e) {
                $response = ['error' => $e->getMessage()];
            } catch (FilesystemException $e) {
                $response = ['error' => $e->getMessage()];
            } finally {
                return $response;
            }
        }
    }

    public function saveWithVariants(Question $question, array $variants = [], array $subtitles = [], UploadedFile|bool|null $questionImage = null, array $variantImages = null, array $subtitleImages = null): Question
    {
        $this->em->persist($question);
        $this->em->flush();

        $this->imageUpdate($question, $this->questionImageUploader, $this->em, $questionImage);
        foreach ($variants as $key => $variant) {
            $this->variantService->imageUpdate($variant, $this->variantImageUploader, $this->em, array_key_exists($key, $variantImages) ? $variantImages[$key] : false);
        }
        foreach ($subtitles as $key => $subtitle) {
            $this->variantService->imageUpdate($subtitle, $this->variantImageUploader, $this->em, array_key_exists($key, $subtitleImages) ? $subtitleImages[$key] : false);
        }
        return $question;

    }

    public function createOrUpdateQuestionIfValid(Question $question, array $data, UploadedFile|bool|null $questionImage = null, array $variantImages = null, array $subtitleImages = null): array
    {
        $response = [];
        $questionErrors = $this->validation->entityWithImageValidate($question, $questionImage instanceof UploadedFile ? $questionImage : null);
        $errors = $questionErrors;

        $variants = [];
        $hasCorrectVariant = [];
        static $i = 0;
        foreach ($data['variant'] ?? [] as $key => $variantData) {
            if (!is_null($question->getType()) && $question->getType()->getTitle() === 'order') {
                $variantData['correct'] = $i++;
            }
            $variant = $this->variantFactory->createBuilder()->buildVariant($variantData, $question, $this->em->find(Variant::class, $key));
            if ($variant->getCorrect()) {
                $hasCorrectVariant[] = $key;
            }
            if (!is_null($variantImages) && isset($variantImages[$variant->getImage()])) {
                $image = $variantImages[$variant->getImage()];
            } else if (!is_null($variantImages) && isset($variantImages[$variant->getId()])) {
                $image = $variantImages[$variant->getId()];
            } else if (!is_null($variantImages) && isset($variantImages[$key])) {
                $image = $variantImages[$key];
            } else {
                $image = null;
            }

            if ($this->validation->entityWithImageValidate($variant, $image)) {
                $errors[] = implode(',', $this->validation->entityWithImageValidate($variant, $image));
            } else {
                $variants[$key] = $variant;
                $question->addVariant($variant);
            }
        }

        foreach ($question->getVariant() ?? [] as $variant) {

            if (!is_null($variant->getId()) && !key_exists($variant->getId(), $variants)) {
                $question->removeVariant($variant);
                $this->em->remove($variant);

            };
        }

        $subtitles = [];
        if (!is_null($question->getType()) && $question->getType()->getTitle() === 'conformity') {
            foreach ($data['subTitle'] ?? [] as $key => $subtitleData) {
                $subtitle = $this->subtitleFactory->createBuilder()->buildSubtitle($subtitleData['title'], $question, isset($subtitleData['variant']) ? $variants[$subtitleData['variant']] ?? null : null, $this->em->find(Subtitle::class, $key));
                if (!is_null($subtitleImages) && isset($subtitleImages[$key])) {
                    $image = $subtitleImages[$key];
                } else {
                    $image = null;
                }

                if ($this->validation->entityWithImageValidate($subtitle, $image)) {
                    $errors[] = implode(',', $this->validation->entityWithImageValidate($subtitle, $image));

                } else {
                    $subtitles[$key] = $subtitle;
                    $question->addSubtitle($subtitle);
                }
            }
        }
        foreach ($question->getSubtitles() as $subtitle) {
            if (!key_exists($subtitle->getId(), $subtitles)) {
                $this->em->remove($subtitle);
            };
        }
        if (count($hasCorrectVariant) < 1 && !in_array($question->getType()?->getTitle(), ['input_one', 'conformity'])) {

            $errors[] = 'Необходимо выбрать хотя бы один верный ответ';
        }

        if (count($hasCorrectVariant) > 1 && $question->getType()?->getTitle() === 'radio') {
            $errors[] = 'Необходимо выбрать только один верный ответ';
        }

        if (!is_null($this->validation->uniqueTitlesValidate($question))) {
            $errors[] = implode(', ', $this->validation->uniqueTitlesValidate($question));

        }
        if ($question->getVariant()->count() < 1) {
            $errors[] = 'Нет соответствующих вариантов ответа (Возможные причины: пустая строка между вопросом и вариантом)';

        }
        $response['error'] = $errors;
        $response['question'] = $question;
        $response['variants'] = $variants;
        $response['subtitles'] = $subtitles;

        return $response;
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
                $response[$question->getPublishedAt() ? 'published' : 'unpublished'][] = $question->getId();
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
        } else {
            $question->setPublishedAt(null);
        }

        return $question->setAuthor($user);

    }

}