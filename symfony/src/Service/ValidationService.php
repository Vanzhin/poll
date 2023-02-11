<?php

namespace App\Service;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidationService
{

    public function __construct(private readonly ValidatorInterface $validator)
    {
    }

    public function isValid(string $data, string $type):bool
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
}