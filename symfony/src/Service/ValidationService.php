<?php

namespace App\Service;

use App\Entity\Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\IsFalse;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Unique;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\FileBag;

class ValidationService
{

    public function __construct(private readonly ValidatorInterface $validator, private readonly EntityManagerInterface $em)
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

    public function questionValidate(array $data, FileBag $files = null): ?array
    {
        $errors = [];
        foreach ($data as $key => $value) {
            if ($key === 'title') {
                $violations = $this->validator->validate($value, [
                    new NotBlank([
                        'message' => 'question.title.not_blank'
                    ]),

                ]);
                foreach ($violations as $violation) {
                    $errors[] = $violation->getMessage();
                }
                continue;
            }
            if ($key === 'type') {
                $violations = $this->validator->validate($this->em->getRepository(Type::class)->findOneBy(['title' => $value]), [
                    new NotNull([
                        'message' => 'question.type.invalid'
                    ]),

                ]);
                foreach ($violations as $violation) {
                    $errors[] = $violation->getMessage();
                }
                continue;

            }
            if ($key === 'variant') {
                if (!is_null($this->variantValidate($value))) {
                    $errors[] = implode(',', $this->variantValidate($value));
                }
            }

        }
        if($files && $files->keys()){
            foreach ($files->keys() as $key){
                if ($files->has($key)) {
                    foreach ($files->get($key)['img'] ?? [] as $image) {
                        if (!is_null($this->imageValidate($image))) {
                            $errors[] = implode(',', $this->imageValidate($image)) ;
                        };
                    };
                }
            }
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

    public function variantValidate(array $data): ?array
    {
        $errors = [];

        $variants = [];
        foreach ($data as $item) {
            $variants[] = $item['title'];
        }

        $variantsWithoutNull = array_filter($variants, fn($value) => !is_null($value) && $value !== '');
        $violations = $this->validator->validate((count(array_unique($variants)) < count($variants) || count($variantsWithoutNull) < count($variants)), [
            new IsFalse([
                'message' => 'question.variants.unique'
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
}