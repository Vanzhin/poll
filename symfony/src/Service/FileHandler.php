<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\File;

class FileHandler
{

    public function getQuestion(File $file)
    {
        $response = [];
        static $section = null;
        static $string = 'question';
        static $questionKey = 0;
        static $variantKey = 0;

        $handle = fopen($file->getPathname(), "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                // process the line read.

                if (strlen(trim($line)) > 0) {
                    $lower = mb_strtolower($line, 'utf8');
                    if (str_contains($lower, 'секция')) {
                        $t = str_replace(['секция', 'Секция', 'СЕКЦИЯ'], '', $line);
                        $section = trim(preg_replace("/[^a-zа-я0-9\s]/iu", "", $t));
//                        $response[] = 'section';
                        continue;
                    }
                    if ($string === 'question') {
                        //
                        $response[$questionKey]['title'] = trim(preg_replace("/[^a-zа-я0-9-?,:\s]/iu", "", $line));
                        $response[$questionKey]['section'] = $section;
                        $string = 'variant';
                    } else {
                        //
                        if (str_contains($line, '*')) {
                            $response[$questionKey]['variant'][$variantKey]['correct'] = true;

                        }
                        $response[$questionKey]['variant'][$variantKey]['title'] = trim(preg_replace("/[^a-zа-я0-9\s]/iu", "", $line));
//                        $response[] = 'variant';
                        $variantKey++;

                    }

                } else {
                    $string = 'question';
                    $questionKey++;
                    $variantKey = 0;

                }
            }

            fclose($handle);
        }

        foreach ($response as $key => $question) {
            $correctCount = 0;
            foreach ($question['variant'] as $variant) {
                if (isset($variant['correct']) && $variant['correct'] === true) {
                    $correctCount++;
                }
            }
            if ($correctCount === 1 && count($question['variant']) > $correctCount) {
                $question['type'] = 'radio';
                $response[$key] = $question;

                continue;
            }
            if ($correctCount > 1 && count($question['variant']) >= $correctCount) {
                $question['type'] = 'checkbox';
                $response[$key] = $question;
                continue;

            }
            if ($correctCount === 1 && count($question['variant']) === $correctCount) {
                $question['type'] = 'input_one';
                $question['answer'] = $question['variant'][0]['title'];
                unset($question['variant']);

                $response[$key] = $question;
                continue;

            }
            if (count($question['variant'])>1 && $correctCount===0) {
                $question['type'] = 'order';

                $response[$key] = $question;
                continue;

            }
        }


        return $response;
    }

}