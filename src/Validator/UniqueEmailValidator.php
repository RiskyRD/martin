<?php

namespace App\Validator;

use App\Model\UserModel;
use Core\Database\DB;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueEmailValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint)
    {
        $db = DB::getInstance();

        $userModel = new UserModel($db);

        if (!$userModel->isEmailUnique($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ email }}', $value)
                ->addViolation();
        }
    }
}
