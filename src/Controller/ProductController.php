<?php

namespace App\Controller;

use Core\HttpFoundation\Redirect;
use Core\HttpFoundation\Request;
use Core\View\Render;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;


class ProductController
{
    public function listProducts(Render $render, Session $session)
    {
        $errors = $session->getFlashBag()->get('errors', []);
        $input = $session->getFlashBag()->get('input', []);
        echo $render->render('product.html.twig', ['errors' => $errors, 'input' => $input]);
        // Code to list products
    }

    public function addProduct(Request $request, Redirect $redirect)
    {
        $validator = Validation::createValidator();
        $fields = [];
        $fields['name'] = $request->request->get('name');
        $fields['stock'] = (int) $request->request->get('stock') ?? 0;
        $fields['price'] = (int) $request->request->get('price') ?? 0;

        $constraint = new Assert\Collection(fields: [
            'name' => new Assert\Sequentially([
                new Assert\NotBlank(),
                new Assert\Type('string'),
            ]),
            'stock' =>
            new Assert\Sequentially([
                new Assert\Type(type: 'integer'),
                new Assert\NotBlank(),
                new Assert\PositiveOrZero(),
            ]),
            'price' =>
            new Assert\Sequentially([
                new Assert\Type(type: 'integer'),
                new Assert\NotBlank(),
                new Assert\PositiveOrZero(),
            ]),
        ], allowMissingFields: false, allowExtraFields: true);
        $violations = $validator->validate($fields, $constraint);
        $errors = [];
        foreach ($violations as $violation) {

            $field = trim($violation->getPropertyPath(), '[]');
            $errors[$field] = $violation->getMessage();
        }
        if (!empty($errors)) {
            return $redirect->with(['errors' => $errors, 'input' => $request->request->all()])->back();
        }
    }
}
