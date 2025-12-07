<?php

namespace App\Middleware;

use Core\Auth\Auth;
use Core\HttpFoundation\Request;
use Core\HttpFoundation\Redirect;

class IsAdminMiddleware implements \Core\HttpFoundation\Middleware
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
        $user = $this->auth->getCurrentUser();

        if (!$user || !$user['is_admin']) {
            return $this->redirect->to('/products');
        }

        return $next($request);
    }
}