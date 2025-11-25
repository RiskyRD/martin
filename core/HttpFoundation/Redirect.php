<?php

namespace Core\HttpFoundation;

use Symfony\Component\HttpFoundation\Session\Session;

class Redirect
{

    protected Request $request;
    protected Session $session;
    public function __construct(Request $request, Session $session)
    {
        $this->request = $request;
        $this->session = $session;
    }

    public function to($url)
    {
        http_response_code(301);
        header("Location: $url");
        exit();
    }

    public function with(array $data)
    {
        foreach ($data as $key => $value) {
            $this->session->getFlashBag()->set($key, $value);
        }
        return $this;
    }

    public function back()
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? $this->session->getFlashBag()->get('old_url', ['/'])[0];
        http_response_code(301);

        header("Location: " . $referer);
        exit();
    }
}
