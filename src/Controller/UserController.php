<?php

namespace App\Controller;

use Core\View\Render;

class UserController
{
    public function user(Render $render)
    {
        echo $render->render('user.html.twig');
    }
}
