<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Question;
use App\Entity\Variant;
use App\Entity\Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints\File as FileConstraint;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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

    public function questionValidate(array $data, File $image = null): ?array
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
        }
        if ($image) {

            if (!is_null($this->imageValidate($image))) {
                $errors[] = implode(',', $this->imageValidate($image));
            };
        };


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

    public function variantValidate(array $data, File $image = null): ?array
    {
        $question = isset($data['questionId']) ? $this->em->find(Question::class, $data['questionId']) : null;
        if (isset($data['questionId']) && is_null($question)) {
            return ['Соответствующий вопрос не найден'];
        } else {
            $errors = [];
            foreach ($data as $key => $value) {
                if ($key === 'title') {
                    $violations = $this->validator->validate($value, [
                        new NotBlank([
                            'message' => 'variant.title.not_blank'
                        ]),

                    ]);
                    if ($question) {
                        $isUnique = !$question->getVariant()->contains($this->em->getRepository(Variant::class)->findOneBy(['title' => $value, 'question' => $question]));
                        $unique = $this->validator->validate($isUnique, [
                            new IsTrue([
                                'message' => 'question.variants.unique'
                            ]),

                        ]);
                        foreach ($unique as $violation) {
                            $errors[] = $violation->getMessage();
                        }
                    }


                    foreach ($violations as $violation) {
                        $errors[] = $violation->getMessage();
                    }
                    continue;
                }
                if ($key === 'weight') {
                    $violations = $this->validator->validate($value, [
                        new LessThanOrEqual([
                            'value' => 100,
                            'message' => "variant.weight.greater_than"
                        ]),

                    ]);
                    foreach ($violations as $violation) {
                        $errors[] = $violation->getMessage();
                    }
                    continue;

                }
                if ($key === 'correct') {
                    $violations = $this->validator->validate($value === 'true' || $value === 'false', [
                        new IsTrue([
                            'message' => 'variant.correct'
                        ]),

                    ]);
                    foreach ($violations as $violation) {
                        $errors[] = $violation->getMessage();
                    }
                    continue;

                }
            }
            if ($image) {

                if (!is_null($this->imageValidate($image))) {
                    $errors[] = implode(',', $this->imageValidate($image));
                };
            };

            if (count($errors) === 0) {
                return null;
            }

            return $errors;
        }

    }

    public function manyVariantsValidate(array $data, array $images = null): ?array
    {
        $errors = [];
        $variantTitles = [];
        foreach ($data['variant'] ?? [] as $key => $variantData) {
            $image = $images[$key] ?? null;
            if (isset($data['questionId'])) {
                $variantData['questionId'] = $data['questionId'];

            }
            $variantTitles[] = $variantData['title'];
            if ($this->variantValidate($variantData ?? [], $image)) {
                $errors[] = implode(',', $this->variantValidate($variantData ?? [], $image));
            }
        }

        if (array_unique($variantTitles) !== $variantTitles) {
            $errors[] = 'Название вариантов не может быть одинаковым';
        }

        if (count($errors) === 0) {
            return null;
        }

        return $errors;
    }

    public function categoryValidate(array $data, File $image = null): ?array
    {

        $errors = [];
        foreach ($data as $key => $value) {
            if ($key === 'title') {
                $violations = $this->validator->validate($value, [
                    new NotBlank([
                        'message' => 'category.title.not_blank'
                    ]),
                    new Length([
                        'min' => 5,
                        'max' => 255,
//
                    ]),

                ]);
                foreach ($violations as $violation) {
                    $errors[] = $violation->getMessage();
                }
                continue;

            }
            if (isset($data['parentId']) && $key === 'parentId') {
                $violations = $this->validator->validate($this->em->getRepository(Category::class)->find($value) instanceof Category, [
                    new IsTrue([
                        'message' => "category.parent.exist"
                    ]),

                ]);
                foreach ($violations as $violation) {
                    $errors[] = $violation->getMessage();
                }
                $parent = $this->em->find(Category::class, $value);
                if ($parent) {
                    $notUniq = $parent->getChildren()->contains($this->em->getRepository(Category::class)->findOneBy(['title' => $data['title'], 'parent' => $parent->getId()]));
                    if ($notUniq) {
                        $errors[] = 'Такое название уже существует в данном разделе';
                    }
                }
                continue;

            }

        }
        if ($image) {

            if (!is_null($this->imageValidate($image))) {
                $errors[] = implode(',', $this->imageValidate($image));
            };
        };

        if (!isset($data['parentId'])) {
            foreach ($this->em->getRepository(Category::class)->findBy(['parent' => null]) as $category) {
                if (array_key_exists('title', $data)) {
                    if ($category->gettitle() === $data['title']) {
                        $errors[] = 'Такое название уже существует в данном разделе';
                    }
                }

            };
        }

        if (count($errors) === 0) {
            return null;
        }

        return $errors;
    }

    public function fileValidate(File $file, string $maxSize = '512k'): ?array
    {
        $errors = [];
        $violations = $this->validator->validate($file, [
            new FileConstraint([
                'extensions' => [
                    'txt' => 'text/plain',
                ],

                'maxSize' => $maxSize,
                'extensionsMessage'=>'file.extension',

            ]),

        ]);
        if($file->guessExtension()!=='txt'){
            $errors[] = 'Кажется, этот не текстовый файл';

        }
        foreach ($violations as $violation) {
            $errors[] = $violation->getMessage();
        }
        if (count($errors) === 0) {
            return null;
        }
        return $errors;
    }
}