<?php

namespace App\Service;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
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
        if ($violations->count() === 0){
            return null;
        }
        foreach ($violations as $violation) {
            $errors[] = $violation->getMessage();
        }
        return $errors;

    }
}