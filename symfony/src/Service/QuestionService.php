<?php

namespace App\Service;

use App\Entity\Question;
use App\Entity\Section;
use App\Entity\Subtitle;
use App\Entity\Test;
use App\Entity\Ticket;
use App\Entity\Type;
use App\Entity\Variant;
use App\Factory\Question\QuestionFactory;
use App\Factory\Subtitle\SubtitleFactory;
use App\Factory\Variant\VariantFactory;
use App\Traits\ImageHandle;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemException;
use Symfony\Component\HttpFoundation\File\File;
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

    public function make(Question $question, array $data): Question
    {
        foreach ($data as $key => $item) {
            if ($key === 'title') {
                $question->setTitle($item);
                continue;
            };
            if ($key === 'type' && $this->em->getRepository(Type::class)->findOneBy(['title' => $item])) {
                $question->setType($this->em->getRepository(Type::class)->findOneBy(['title' => $item]));
                continue;

            };
//            if ($key === 'answer' && $question->getType()) {
//                if (in_array($question->getType()->getTitle(), ['input_one', 'input_many'])) {
//                    $question->setAnswer($item);
//                }
//                continue;
//
//            };
            if ($key === 'ticket') {
                foreach ($question->getTickets() as $ticket) {
                    $question->removeTicket($ticket);
                }
                foreach ($item as $ticketId) {
                    $ticket = $this->em->getRepository(Ticket::class)->find($ticketId);
                    if ($ticket) {
                        $question->addTicket($ticket);
                    }

                }
                continue;

            }
            if ($key === 'subTitle') {
                $question->setSubTitle(array_values($item));
                continue;

            }
            if ($key === 'section') {
                $question->setSection($this->em->getRepository(Section::class)->find($item));
                continue;

            }
            if ($key === 'test') {
                $question->setTest($this->em->getRepository(Test::class)->find($item));
                continue;

            }
            if ($key === 'published') {
                $question->setPublishedAt(new \DateTime('now'));
                continue;

            }
        }
        return $question;
    }

    public function delete(Question $question): void
    {
//        foreach ($question->getVariant() as $variant) {
//            $this->variantImageUploader->delete($variant->getImage());
//        };
//
//        $this->questionImageUploader->delete($question->getImage());
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

    public function saveWithVariant(Question $question, ?array $questionData, ?array $variantData, UploadedFile $questionImage = null, array $variantImages = []): Question
    {
        $question = $this->questionFactory->createBuilder()->buildQuestion($questionData ?? [], $question);
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

    public function saveWithVariantIfValid(Question $question, array $data, UploadedFile|bool|null $questionImage = null, array $variantImages = null): array
    {
        if (!$question->getId()) {
            $message = 'Вопрос создан';
        } else {
            $message = 'Вопрос обновлен';
        }
        $question = $this->questionFactory->createBuilder()->buildQuestion($data['question'] ?? [], $question);
        $questionErrors = $this->validation->entityWithImageValidate($question, $questionImage instanceof UploadedFile ? $questionImage : null);
        $errors = $questionErrors;
        $this->em->beginTransaction();
        $this->em->persist($question);
        $this->em->flush();
        $variants = [];
        static $i = 0;
        foreach ($data['variant'] ?? [] as $key => $variantData) {
            $variantData['questionId'] = $question->getId();
            if ($question->getType()->getTitle() === 'order') {
                $variantData['correct'] = $i++;
            }
            $variant = $this->variantFactory->createBuilder()->buildVariant($variantData, $this->em->find(Variant::class, $key));
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

//                $this->variantService->questionAnswerUpdate($variant, true);
            }
        }

        foreach ($question->getVariant() as $variant) {
            if (!key_exists($variant->getId(), $variants)) {
                $this->em->remove($variant);
                $this->em->flush();
            };
        }

//        if($question->getType()->getTitle() === 'conformity'){
//            foreach ($variants as $key=>$variantId){
//
//            }
//        }
//        dd($question->getVariant()->count());
//      todo with subtitles
//        foreach ($data['subTitle'] ?? [] as $key => $subTitleData) {
//            $subTitleData['questionId'] = $question->getId();
//            $this->subtitleFactory->createBuilder()->buildSubtitle($subTitleData['title'],$this->em->find(Subtitle::class,$key));
//
//            if (!is_null($variantImages) && isset($variantImages[$key])) {
//                $image = $variantImages[$key];
//            } else {
//                $image = null;
//            }
//
//            if ($this->validation->entityWithImageValidate($variant, $image)) {
//                $errors[] = implode(',', $this->validation->entityWithImageValidate($variant, $image));
//
//            } else {
//                $variants[$key] = $variant;
//                $this->em->persist($variant);
//                $this->em->flush();
////                $this->variantService->questionAnswerUpdate($variant, true);
//            }
//        }

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
//        $sectionTitle = '';
//        foreach ($questions as $key => $question){
//
//            dd(is_null($question->getSection())    , $question->getSection());
//            if(is_null(!$question->getSection())?? $question->getSection()->getTitle() !== $sectionTitle){
//                $sectionTitle = $question->getSection()->getTitle();
//                $info['section'][]['title'] = $sectionTitle;
//                $info['section'][]['question'] = 1;
//                dd($info);
//
//            }else{
//                $info['section'][]['question']++;
//
//            }
//        }
        return $info;

    }


}