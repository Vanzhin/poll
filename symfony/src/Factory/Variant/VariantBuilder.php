<?php

namespace App\Factory\Variant;

use App\Entity\Question;
use App\Entity\Variant;
use Doctrine\ORM\EntityManagerInterface;

class VariantBuilder
{
    public function __construct(private readonly EntityManagerInterface $em)
    {
    }

    public function buildVariant(array $data, Variant $variant = null): Variant
    {

        if (!$variant) {
            $variant = new Variant();
        }

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
            if ($key === 'correct') {
                $variant->setIsCorrect(true);
                if (is_numeric($item)) {
                    $variant->setCorrect($item);

                } else {
//                    todo
                    if (strlen($item) > 0) {
                        if ($item === 'true') {
                            $variant->setCorrect(1);

                        } elseif ($item === 'false') {
                            $variant->setCorrect(null);

                        } else {
                            $variant->setCorrect(-1);
                        }
                    }
                }
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

}