<?php

namespace App\Factory\Variant;

use App\Entity\Question;
use App\Entity\Variant;

class VariantBuilder
{
    public function __construct()
    {
    }

    public function buildVariant(array $data, Question $question, Variant $variant = null): Variant
    {

        if (!$variant) {
            $variant = new Variant();
        }
        foreach ($data as $key => $item) {
            if ($key === 'title') {
                $variant->setTitle($item);
                continue;
            };
            if ($key === 'image') {
                $variant->setImage($item);
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
        $variant->setQuestion($question);
        return $variant;
    }

}