<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\File;

class FileHandler
{

    public function getQuestion(File $file): array
    {
        $response = [];
        $encoding = $this->detectEncoding($file);
        static $section = null;
        static $string = 'question';
        static $questionKey = 0;
        static $variantKey = 0;
        $handle = fopen($file->getPathname(), "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                // process the line read.

                if (strlen(trim($line)) > 0) {
                    if ($encoding !== 'utf8') {
                        $line = mb_convert_encoding($line, 'utf8', $encoding);
                    }
                    $lower = trim(mb_strtolower($line));
                    if (str_starts_with($lower, 'секция')) {
                        $t = str_replace(['секция', 'Секция', 'СЕКЦИЯ', '«', '»', '"'], '', $line);
                        $section = trim(preg_replace("/^([(|.]?([[:alnum:]])+(?)[)|.]+)|((([.:;]|[[:space:]])*)$)/iu", "", $t));
                        continue;
                    }
                    if ($string === 'question') {
                        //
                        $response[$questionKey]['title'] = trim(preg_replace("/^[(|.]?([[:alnum:]])+(?)[).]+/iu", "", $line));
                        if ($section) {
                            $response[$questionKey]['section'] = $section;

                        }
                        $string = 'variant';
                    } else {
                        //
                        if (str_starts_with($lower, '*')) {
                            $response[$questionKey]['variant'][$variantKey]['correct'] = 1;
                            $line = str_replace('*', '', $line);
                        }

                        if (str_starts_with($lower, '#')) {
                            if (preg_match("/^(#+\d+)/m", $line, $matches) > 0) {
                                $correct = (trim($matches[0], '#'));
                                $response[$questionKey]['variant'][$variantKey]['correct'] = ($correct - 1);

                            };
                        }


                        $response[$questionKey]['variant'][$variantKey]['title'] = trim(preg_replace("/(^[*).#]+\d?)|((([.:;]|[[:space:]])*)$)/iu", "", $line));
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
            $correctCount = [];
            if (key_exists('variant', $question)) {

                foreach ($question['variant'] as $variant) {
                    if (isset($variant['correct']) && $variant['correct'] >= 0) {
                        $correctCount[] = $variant['correct'];
                    }
                }
                if (count($correctCount) === 1 && count($question['variant']) > count($correctCount)) {
                    $question['type'] = 'radio';
                    $response[$key] = $question;

                    continue;
                }
                if (count($correctCount) > 1 && count($question['variant']) >= count($correctCount) && count(array_unique($correctCount)) === 1) {
                    $question['type'] = 'checkbox';
                    $response[$key] = $question;
                    continue;

                }
                if (count($correctCount) === 1 && count($question['variant']) === count($correctCount)) {
                    $question['type'] = 'input_one';
                    $question['answer'] = $question['variant'][0]['title'];
                    unset($question['variant']);

                    $response[$key] = $question;
                    continue;

                }
                if (count($question['variant']) > 1 && count($correctCount) === count($question['variant']) && count(array_unique($correctCount)) == count($correctCount)) {

                    $question['type'] = 'order';

                    $response[$key] = $question;
                    continue;

                }
            }
        }

        return $response;
    }

    private function detectEncoding(File $file, array $encoding = ['utf8', 'windows-1251']): string
    {
        return mb_detect_encoding($file->getContent(), $encoding);
    }


}