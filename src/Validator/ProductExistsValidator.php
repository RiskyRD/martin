<?php

namespace App\Validator;

use App\Model\ProductModel;
use Core\Database\DB;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ProductExistsValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint)
    {
        $db = DB::getInstance();

        $productModel = new ProductModel($db);

        if (!$productModel->isProductExists($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ product }}', $value)
                ->addViolation();
        }
    }
}
