<?php

namespace Core\Auth;

use Core\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\Security\Csrf\TokenGenerator\UriSafeTokenGenerator;
use Symfony\Component\Security\Csrf\TokenStorage\SessionTokenStorage;

class Auth
{
    protected User $userModel;
    protected Session $session;
    protected $currentUser;
    protected CsrfTokenManager $csrfTokenManager;
    protected Request $request;

    public function __construct(Session $session, User $userModel, Request $request)
    {
        $this->session = $session;
        $this->userModel = $userModel;
        $this->request = $request;

        $requestStack = new RequestStack();
        $requestStack->push($request);
        $storage = new SessionTokenStorage($requestStack);
        $this->csrfTokenManager = new CsrfTokenManager(new UriSafeTokenGenerator(), $storage);
    }


    public function getCurrentUser()
    {
        if (!$this->currentUser) {
            $id = $this->session->get('user_id');
            $this->currentUser = $this->userModel->getUserById($id);
        }
        return $this->currentUser;
    }

    public function login(int $id)
    {
        $this->session->set('user_id', $id);
    }

    public function regenerate()
    {
        $this->session->migrate();
    }

    public function regenerateCsrfToken()
    {
        return $this->csrfTokenManager->refreshToken('csrf_token')->getValue();
    }

    public function getCsrfToken()
    {
        return $this->csrfTokenManager->getToken('csrf_token')->getValue();
    }

    public function isCsrfTokenValid($token)
    {
        return $this->csrfTokenManager->isTokenValid(new CsrfToken('csrf_token', $token));
    }
}
