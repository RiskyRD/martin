<?php

namespace Core\Route;

use Core\HttpFoundation\Middleware;
use Exception;

class Route
{
    protected $routes = [];
    protected $attr = [];
    protected $method = '';
    protected $path = '';
    protected $compiledPath = '';
    protected $handler;
    protected $paramSegments;

    public function __construct()
    {
        $this->attr['middlewares'] = [];
    }

    public function middleware($middlewares)
    {
        foreach ((array) $middlewares as $middleware) {
            $interfaces = class_implements($middleware);
            if ($interfaces === false || !in_array(Middleware::class, $interfaces)) {
                throw new Exception("Middleware " . $middleware . " must implement Core\HttpFoundation\Middleware interface.");
            }
            $this->attr['middlewares'][] =  $middleware;
        }
        return $this;
    }

    protected function compileRoute($path)
    {
        preg_match_all('/[^\/]+/', $path, $matches);
        $segments = $matches[0];

        $regexParts = [];
        foreach ($segments as $segment) {
            if (preg_match('/^{(.+)}$/', $segment, $paramMatch)) {
                $paramName = $paramMatch[1];
                $regexParts[] = '(?P<' . $paramName . '>[^/]+)';
            } else {
                $regexParts[] = preg_quote($segment, '/');
            }
        }
        $regex = '#^\/' . implode('\/', $regexParts) . '$#';
        return $regex;
    }


    protected function registerRoute($method, $path, $handler)
    {
        $this->method = $method;
        $this->path = $path;
        $this->compiledPath = $this->compileRoute($path);
        $this->handler = $handler;
        AppRoute::register($this);
    }

    public function get(String $path, array $handler)
    {
        $this->registerRoute('get', $path, $handler);
        return $this;
    }


    public function post(String $path, array $handler)
    {
        $this->registerRoute('post', $path, $handler);
    }

    public function put(String $path, array $handler)
    {
        $this->registerRoute('put', $path, $handler);
    }

    public function delete(String $path, array $handler)
    {
        $this->registerRoute('delete', $path, $handler);
    }

    public function getCompiledPath()
    {
        return $this->compiledPath;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getHandler()
    {
        return $this->handler;
    }

    public function getMiddlewares()
    {
        return $this->attr['middlewares'];
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getParamSegment()
    {
        if (!$this->paramSegments) {
            preg_match_all('/{(.+?)}/', $this->path, $matches);
            $this->paramSegments = $matches[1];
        }
        return $this->paramSegments;
    }
}
