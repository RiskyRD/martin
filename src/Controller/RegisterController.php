<?php

namespace App\Controller;

use App\Model\UserModel;
use App\Validator\UniqueEmail;
use Core\HttpFoundation\Redirect;
use Core\HttpFoundation\Request;
use Core\View\Render;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterController
{

    public function registerPage(Render $render, Session $session)
    {
        $errors = $session->getFlashBag()->get('errors', []);
        echo $render->render('register.html.twig', ['errors' => $errors]);
    }

    public function registerUser(Request $request, Redirect $redirect, UserModel $userModel)
    {
        $validator = Validation::createValidator();

        $constraint = new Assert\Collection(fields: [
            'username' => new Assert\Sequentially([
                new Assert\Type('string'),
                new Assert\NotBlank(),
                new Assert\Length(['min' => 3, 'max' => 50])
            ]),
            'password' => new Assert\Sequentially([
                new Assert\Type('string'),
                new Assert\NotBlank(),
                new Assert\Length(['min' => 6])
            ]),
            'email' =>
            new Assert\Sequentially([
                new Assert\Type('string'),
                new Assert\NotBlank(),
                new Assert\Email(),
                new UniqueEmail(),
            ]),
        ], allowMissingFields: false, allowExtraFields: true);
        $violations = $validator->validate($request->request->all(), $constraint);
        $errors = [];
        foreach ($violations as $violation) {

            $field = trim($violation->getPropertyPath(), '[]');
            $errors[$field] = $violation->getMessage();
        }
        if (!empty($errors)) {
            return $redirect->with(['errors' => $errors])->back();
        }
        $userModel->saveUser($request->request->all());

        return $redirect->to('/login');
    }
}
