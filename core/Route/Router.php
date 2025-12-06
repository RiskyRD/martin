<?php

namespace Core\Route;

/**
 * Class Router
 * @package Core\Route
 * @method static Route get(string $path, Array $handler)
 * @method static Route post(string $path, Array $handler)
 * @method static Route middleware(Array $middlewares)
 */
class Router
{
    protected $allowedMethods = ['get', 'post', 'put', 'delete', 'patch', 'options', 'middleware'];
    protected $middlewares = [];

    public static function __callStatic($name, $arguments)
    {
        if (in_array(strtolower($name), (new self())->allowedMethods)) {
            $router = new Route();
            $router->{$name}(...$arguments);

            return $router;
        }
    }
}
