<?php

namespace App\Service;

use App\Entity\Question;
use App\Interfaces\EntityWithImageInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints\File as FileConstraint;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidationService
{

    public function __construct(private readonly ValidatorInterface $validator)
    {
    }

    public function isValid(string $data, string $type): bool
    {
        $response = false;
        switch ($type) {
            case 'email':
                $violations = $this->validator->validate($data, [
                    new Email(),
                ]);

                if (0 === count($violations)) {
                    $response = true;
                }
                break;

        }
        return $response;

    }

    public function validate(object $entity): array
    {
        $response = [];
        $errors = $this->validator->validate($entity);

        foreach ($errors as $error) {
            $response[] = $error->getMessage();

        }
        return $response;

    }

    public function userPasswordValidate(string $data): ?array
    {
        $errors = [];

        $violations = $this->validator->validate($data, [
            new NotBlank([
                'message' => 'user.password.not_blank'
            ]),
            new Length([
                'min' => 6,
                'minMessage' => 'user.password.length'
            ]),
            new Regex([
                'pattern' => '/^[A-Za-z0-9_]+$/',
                'message' => 'user.password.regex'
            ])
        ]);
        if ($violations->count() === 0) {
            return null;
        }
        foreach ($violations as $violation) {
            $errors[] = $violation->getMessage();
        }
        return $errors;

    }

    public function entityWithImageValidate(EntityWithImageInterface $entity, File $image = null, string $imageSize = '1M'): ?array
    {
        $errors = $this->validate($entity);
        if ($image) {

            if (!is_null($this->imageValidate($image, $imageSize))) {
                $errors[] = implode(',', $this->imageValidate($image));
            };
        };
        if (!$entity->getId() && ($entity->getImage() && is_null($image))) {
            $errors[] = sprintf('Файл %s не обнаружен', $entity->getImage());
        }


        if (count($errors) === 0) {
            return null;
        }
        return $errors;
    }

    public function imageValidate(File $file, string $maxSize = '1M'): ?array
    {
        $errors = [];
        $violations = $this->validator->validate($file, [
            new Image([
                'maxSize' => $maxSize,
                'maxSizeMessage' => 'file.size.max',
            ]),

        ]);
        foreach ($violations as $violation) {
            $errors[] = $violation->getMessage();
        }
        if (count($errors) === 0) {
            return null;
        }
        return $errors;
    }

    public function fileValidate(?File $file, string $maxSize = '512k'): array
    {
        $errors = [];
        $violations = $this->validator->validate($file, [
            new FileConstraint([
                'extensions' => [
                    'txt' => 'text/plain',
                    'zip' => 'application/zip'
                ],

                'maxSize' => $maxSize,
                'extensionsMessage' => 'file.extension',

            ]),
            new NotNull([
                'message' => 'file.is.null'
            ])

        ]);

        foreach ($violations as $violation) {
            $errors[] = $violation->getMessage();
        }
        return $errors;
    }

    public function uniqueTitlesValidate(Question $question): ?array
    {
        $errors = [];
        $variants = [];
        $subtitles = [];
        foreach ($question->getVariant() as $variant) {
            $variants[] = $variant->getTitle();
        }
        foreach ($question->getSubtitles() as $subtitle) {
            $subtitles[] = $subtitle->getTitle();
        }
        if (count(array_unique($variants)) !== count($variants)) {
            $errors[] = 'Названия вариантов не могут быть одинаковыми';
        }
        if (count(array_unique($subtitles)) !== count($subtitles)) {
            $errors[] = 'Названия подвопросов не могут быть одинаковыми';
        }
        if (count($errors) === 0) {
            return null;
        }
        return $errors;
    }

}