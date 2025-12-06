<?php

namespace App\Middleware;

use Core\Auth\Auth;
use Core\HttpFoundation\Redirect;
use Core\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware implements \Core\HttpFoundation\Middleware
{
    protected Auth $auth;
    protected Redirect $redirect;
    public function __construct(Auth $auth, Redirect $redirect)
    {
        $this->auth = $auth;
        $this->redirect = $redirect;
    }

    public function handle(Request $request, callable $next)

    {
        if ($this->auth->getCurrentUser() === null) {
            $this->redirect->to('/login');
            return;
        }
        return $next($request);
    }
}
