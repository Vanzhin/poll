<?php

namespace App\Service;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
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

        for ($i = 0; $i < count($errors); $i++) {
            $response[] = $this->validator->validate($entity)->get($i)->getMessage();

        }
        return $response;

    }

    public function userPasswordValidate(string $data): ?array
    {
        $errors = [];

        $violations = $this->validator->validate($data, [
            new NotBlank([
                'message' => 'Пароль не может быть пустым.'
            ]),
            new Length([
                'min' => 6,
                'minMessage' => 'Пароль должен быть не менее 6 символов.'
            ])
        ]);
        if ($violations->count() === 0){
            return null;
        }
        for ($i = 0; $i < count($violations); $i++) {
            $errors[] = $violations->get($i)->getMessage();
        }
        return $errors;

    }
}