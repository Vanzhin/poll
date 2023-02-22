<?php

namespace App\Service;

use App\Entity\Question;
use App\Entity\Ticket;
use App\Entity\Type;
use App\Entity\Variant;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemException;
use Symfony\Component\HttpFoundation\FileBag;

class QuestionService
{

    public function __construct(private readonly EntityManagerInterface $em, private readonly FileUploader $questionImageUploader)
    {
    }

    /**
     * @throws FilesystemException
     */
    public function save(Question $question, array $data, FileBag $files = null): Question
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
                if ($question->getVariant()->count() > 0) {
                    $answers = [];
                    foreach ($item as $variantId) {
                        $answer = $this->em->getRepository(Variant::class)->find($variantId);
                        if ($answer) {
                            $answers[] += $variantId;

                        }
                    }
                    $question->setAnswer($answers);
                }
                continue;

            };
            if ($key === 'ticket') {
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

        if ($files && $files->keys()) {
            foreach ($files->keys() as $key) {
                if ($key === 'question') {
                    foreach ($files->get($key)['img'] ?? [] as $image) {
                        $question->setImage($this->questionImageUploader->uploadImage($image));
                    };
                }
            }
        }


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
}