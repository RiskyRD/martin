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


class UserController
{
    public function userCreateView(Render $render, Session $session)
    {
        $errors = $session->getFlashBag()->get('errors', []);
        $errorsCredentials = $session->getFlashBag()->get('errorsCredentials', [])[0] ?? null;
        $input = $session->getFlashBag()->get('input', []);
        echo $render->render('userCreate.html.twig', ['errors' => $errors, 'errorsCredentials' => $errorsCredentials, 'input' => $input]);
    }

    public function userCreate(Render $render, Request $request, Redirect $redirect, UserModel $userModel)
    {
        $validator = Validation::createValidator();
        $constraint = new Assert\Collection(fields: [
            'password' => new Assert\Sequentially([
                new Assert\Type('string'),
                new Assert\NotBlank(),
            ]),
            'email' =>
            new Assert\Sequentially([
                new Assert\Type('string'),
                new Assert\NotBlank(),
                new Assert\Email(),
                new UniqueEmail(),
            ]),
            'name' =>
            new Assert\Sequentially([
                new Assert\Type('string'),
                new Assert\NotBlank(),
                new Assert\Length(max: 100),
            ]),
            'telephone' =>
            new Assert\Sequentially([
                new Assert\Type('string'),
                new Assert\NotBlank(),
            ]),
            'role' =>
            new Assert\Sequentially([
                new Assert\Type('string'),
                new Assert\NotBlank(),
            ]),
        ], allowMissingFields: false, allowExtraFields: true);
        $violations = $validator->validate($request->request->all(), $constraint);
        $errors = [];
        $reqForm = $request->request->all();
        $reqForm['is_admin'] = $reqForm['role'] === 'admin' ? true : false;
        foreach ($violations as $violation) {

            $field = trim($violation->getPropertyPath(), '[]');
            $errors[$field] = $violation->getMessage();
        }
        if (!empty($errors)) {
            return $redirect->with(['errors' => $errors, 'input' => $request->request->all()])->back();
        }
        $userModel->saveUser($reqForm);
        return $render->render('userCreate.html.twig');
    }

    public function listUsers(Render $render,)
    {
        echo $render->render('user.html.twig');
    }
}
