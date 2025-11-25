<?php

namespace App\Controller;

use Core\Auth\Auth;
use Core\HttpFoundation\Request;
use Core\View\Render;

class HomeController
{
    public function index(Render $render)
    {
        echo $render->render('home.html.twig');
    }

    public function paramTester(Request $request, Render $render, Auth $auth)
    {
        echo $_SERVER['HTTP_REFERER'];
        echo $auth->isCsrfTokenValid($request->request->get('csrf_token')) ? 'Valid CSRF Token' : 'Invalid CSRF Token';
        // echo $render->render('param_tester.html.twig', ['id' => $id]);
    }

    public function paramForm(Render $render, Auth $auth)
    {
        echo $render->render('form-test.html.twig');
    }
}
