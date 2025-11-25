<?php

namespace App\Middleware;

use Core\Auth\Auth;
use Core\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CsrfValid implements \Core\HttpFoundation\Middleware
{
    protected Auth $session;
    public function __construct(Auth $auth)
    {
        $this->session = $auth;
    }

    public function handle(Request $request, callable $next)

    {
        if (!$this->session->isCsrfTokenValid($request->request->get('csrf_token'))) {
            $response = new Response();
            $response->setContent('Invalid CSRF Token');
            $response->setStatusCode(Response::HTTP_FORBIDDEN);

            $response->prepare($request);
            $response->send();
            return;
        }
        return $next($request);
    }
}
