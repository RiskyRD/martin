<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class UniqueEmail extends Constraint
{
    public string $message = 'The email "{{ email }}" is already in use.';

    public function getTargets(): string|array
    {
        return self::CLASS_CONSTRAINT;
    }
}
