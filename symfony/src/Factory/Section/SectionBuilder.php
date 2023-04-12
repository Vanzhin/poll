<?php

namespace App\Factory\Section;

use App\Entity\Question;
use App\Entity\Section;
use App\Entity\Test;
use Doctrine\ORM\EntityManagerInterface;

class SectionBuilder
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function buildSection(array $data, Section $section = null): Section
    {
        if (!$section) {
            $section = new Section();
        }


        foreach ($data as $key => $item) {
            if ($key === 'title') {
                $section->setTitle($item);
                continue;
            };
            if ($key === 'test') {
                $section->setTest($this->em->find(Test::class, $item));
                continue;
            }

            if ($key === 'questionCountToPass') {
                $section->setQuestionCountToPass(intval($item));
                continue;
            }
            if ($key === 'question' && is_array($item)) {
                foreach ($section->getQuestions() as $question) {
                    $section->removeQuestion($question);
                }

                foreach ($item as $questionId) {
                    $question = $this->em->find(Question::class, $questionId);
                    if ($question) {
                        $section->addQuestion($question);

                    };

                }
            }

        }
        return $section;
    }

}