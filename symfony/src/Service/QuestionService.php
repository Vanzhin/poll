<?php

namespace App\Service;

use App\Entity\Question;
use App\Entity\Ticket;
use App\Entity\Type;
use App\Entity\Variant;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class QuestionService
{

    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly FileUploader $questionImageUploader,
        private readonly FileUploader $variantImageUploader
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
                if(in_array($question->getType()->getTitle(),['input_one', 'input_many'])){
                    $question->setAnswer($item);
                }
                continue;

            };
            if ($key === 'ticket') {
                foreach ($question->getTickets() as $ticket){
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

            }
        }

        if ($image) {
            $question->setImage($this->questionImageUploader->uploadImage($image, $question->getImage()));
        };


        $this->em->persist($question);
        $this->em->flush();
        return $question;
    }

    public function getAnswerIds(Question $question, array $data): array
    {
        $answers = [];
        foreach ($data['answer'] ?? [] as $key) {
            $answer = $this->em->getRepository(Variant::class)->findOneBy(['title' => $data['variant'][$key]['title'], 'question' => $question->getId()]);
            if ($answer) {
                $answers[] += $answer->getId();

            }
        }

        return $answers;
    }
    public function delete(Question $question): void
    {
        foreach($question->getVariant() as $variant){
            $this->variantImageUploader->delete($variant->getImage());
        };

        $this->questionImageUploader->delete($question->getImage());
        $this->em->remove($question);
        $this->em->flush();
    }
}