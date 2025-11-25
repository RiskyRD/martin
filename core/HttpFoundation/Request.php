<?php

namespace Core\HttpFoundation;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class Request extends SymfonyRequest
{
    protected $params = [];
    public function getParam($key, $default = null)
    {
        return $this->params[$key] ?? $default;
    }

    public function setParam($key, $value)
    {
        $this->params[$key] = $value;
    }
}
