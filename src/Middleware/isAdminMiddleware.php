<?php

namespace App\Middleware;

use Core\Auth\Auth;
use Core\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdminMiddleware implements \Core\HttpFoundation\Middleware
{
    protected Auth $auth;
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function handle(Request $request, callable $next)

    {
        if (!$this->auth->getCurrentUser()['is_admin']) {
            $response = new Response();
            $response->setContent('Access denied. Admins only.');
            $response->setStatusCode(Response::HTTP_FORBIDDEN);

            $response->prepare($request);
            $response->send();
            return;
        }
        return $next($request);
    }
}
