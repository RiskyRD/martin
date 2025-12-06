<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class ProductExists extends Constraint
{
    public string $message = 'The product does not exist.';

    public function getTargets(): string|array
    {
        return self::CLASS_CONSTRAINT;
    }
}
