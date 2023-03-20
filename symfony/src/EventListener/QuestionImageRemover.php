<?php

namespace App\EventListener;

use App\Entity\Question;
use App\Service\FileUploader;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use League\Flysystem\FilesystemException;

#[AsEntityListener(event: Events::postRemove, method: 'postRemove', entity: Question::class)]
class QuestionImageRemover
{
    public function __construct(private readonly FileUploader $questionImageUploader)
    {
    }

    /**
     * @throws FilesystemException
     */
    public function postRemove(Question $question): void
    {
        // if this listener only applies to certain entity types,
        // add some code to check the entity type as early as possible
        if ($question->getImage()) {
            $this->questionImageUploader->delete($question->getImage());
        }
    }


}