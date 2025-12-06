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
                new Assert\Length(max: 15),
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
        return $render->render('user.html.twig');
    }

    public function listUsers(Render $render, UserModel $userModel)
    {
        $user = $userModel->getAllUsers();
        echo $render->render('user.html.twig', ['users' => $user]);
    }

    public function updateUserView(Render $render, Request $request, Redirect $redirect, UserModel $userModel, Session $session)
    {
        $userId = (int)$request->getParam('id');
        $userData = $userModel->getUserById($userId);
        unset($userData['password']);
        if (!$userData) {
            return $redirect->to('/notFound');
        }
        $errors = $session->getFlashBag()->get('errors', []);
        $input = $session->getFlashBag()->get('input', []);
        $userData = array_replace($userData, $input);
        echo $render->render('userUpdate.html.twig', ['input' => $userData, 'id' => $userId, 'errors' => $errors]);
    }

    public function userUpdate(Request $request, Redirect $redirect, UserModel $userModel)
    {
        $userId = (int)$request->getParam('id');
        $userData = $userModel->getUserById($userId);
        if (!$userData) {
            return $redirect->to('/notFound');
        }

        $validator = Validation::createValidator();
        $constraint = new Assert\Collection(fields: [

            'email' =>
            new Assert\Sequentially([
                new Assert\Type('string'),
                new Assert\NotBlank(),
                new Assert\Email(),
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
                new Assert\Length(max: 15),
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
        $newUser = $userModel->getUserByEmail($reqForm['email']);
        if ($newUser && $newUser['id'] != $userId) {
            $errors['email'] = 'This email is already in use.';
        }
        foreach ($violations as $violation) {

            $field = trim($violation->getPropertyPath(), '[]');
            $errors[$field] = $violation->getMessage();
        }
        if (!empty($errors)) {
            return $redirect->with(['errors' => $errors, 'input' => $request->request->all()])->back();
        }
        if (array_key_exists('password', $reqForm) && !empty($reqForm['password'])) {
            $reqForm['password'] = password_hash($reqForm['password'], PASSWORD_BCRYPT);
        } else {
            $reqForm['password'] = $userData['password']; // Retain existing password
        }
        $userModel->updateUser($userId, $reqForm);
        return $redirect->to('/user');
    }

    public function deleteUser(Request $request, Redirect $redirect, UserModel $userModel)
    {
        $userId = (int)$request->getParam('id');
        $userData = $userModel->getUserById($userId);
        if (!$userData) {
            return $redirect->to('/notFound');
        }
        $userModel->delete($userId);
        return $redirect->to('/user');
    }
}
