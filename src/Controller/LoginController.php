<?php

namespace App\Controller;

use App\Model\UserModel;
use Core\Auth\Auth;
use Core\View\Render;
use Symfony\Component\Validator\Validation;
use Core\HttpFoundation\Redirect;
use Core\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Constraints as Assert;

class LoginController
{

    public function loginPage(Render $render, Session $session)
    {
        $errors = $session->getFlashBag()->get('errors', []);
        $errorsCredentials = $session->getFlashBag()->get('errorsCredentials', [])[0] ?? null;
        echo $render->render('login.html.twig', ['errors' => $errors, 'errorsCredentials' => $errorsCredentials]);
    }

    public function loginUser(Request $request, Redirect $redirect, UserModel $userModel, Auth $auth)
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

        $user = $userModel->getUserByEmail($request->request->get('email'));

        if (!$user || !password_verify($request->request->get('password'), $user['password'])) {
            return $redirect->with(['errorsCredentials' => 'Invalid email or password.'])->back();
        }

        $auth->login($user['id']);
        $auth->regenerate();
        $auth->regenerateCsrfToken();

        $redirect->to('/');
    }
}
