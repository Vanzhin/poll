<?php

namespace App\Service;

use App\Entity\Question;
use App\Entity\Section;
use App\Entity\Test;
use App\Entity\Ticket;
use App\Entity\Type;
use App\Entity\Variant;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class QuestionService
{

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly FileUploader           $questionImageUploader,
        private readonly FileUploader           $variantImageUploader,
        private readonly VariantService         $variantService
    )
    {
    }

    /**
     * @throws FilesystemException
     */
    public function save(Question $question, array $data, UploadedFile $image = null): Question
    {
        foreach ($data as $key => $item) {
            if ($key === 'title') {
                $question->setTitle($item);
                continue;
            };
            if ($key === 'type') {
                $question->setType($this->em->getRepository(Type::class)->findOneBy(['title' => $item]));
                continue;

            };
            if ($key === 'answer') {
                if (in_array($question->getType()->getTitle(), ['input_one', 'input_many'])) {
                    $question->setAnswer($item);
                }
                continue;

            };
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
                $question->setSubTitle($item);
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
        }

        if ($image) {
            $question->setImage($this->questionImageUploader->uploadImage($image, $question->getImage()));
        };

        $this->em->persist($question);
        $this->em->flush();
        return $question;
    }

    public function delete(Question $question): void
    {
        foreach ($question->getVariant() as $variant) {
            $this->variantImageUploader->delete($variant->getImage());
        };

        $this->questionImageUploader->delete($question->getImage());
        $this->em->remove($question);
        $this->em->flush();
    }

    public function saveResponse(Question $question, array $data, ?UploadedFile $image): array
    {
        try {
            if ($question->getId()) {
                $message = 'Вопрос обновлен';
            } else {
                $message = 'Вопрос создан';

            }
            $question = $this->save($question, $data ?? [], $image);
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
        $question = $this->save($question, $questionData ?? [], $questionImage);
        foreach ($variantData as $key => $variantItem) {
            $image = $variantImages[$key] ?? null;
            $variantItem['questionId'] = $question->getId();
            $variant = $this->variantService->save(new Variant(), $variantItem ?? [], $image);
            if ($question->getType()->getTitle() === 'order') {
                $answers[] = $variant->getId();
            }
        }
        if ($question->getType()->getTitle() === 'order') {
            $question->setAnswer($answers);
            $this->em->persist($question);
            $this->em->flush();
        }
        return $question;
    }

    public function getUploadedQuestionsSummary(array $questions): array
    {
        $info = [];
        $info['message'] = 'Загружено ' . count($questions) . ' вопросов';
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