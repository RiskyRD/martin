<?php

namespace Core\Route;

use Core\HttpFoundation\Request;
use Core\Service;
use Symfony\Component\HttpFoundation\Session\Session;

class AppRoute
{
    public static array $routes = [];

    public static function register(Route $route)
    {
        self::$routes[] = $route;
    }

    public static function buildPipeline(array $middlewares, array $handler)
    {
        $service = Service::getInstance();

        $next = function () use ($handler, $service) {
            return $service->call($handler);
        };

        foreach (array_reverse($middlewares) as $middleware) {
            $next = function () use ($middleware, $service, $next) {
                $middlewareInstance = $service->get($middleware);
                $request = $service->get(Request::class);
                return $middlewareInstance->handle($request, $next);
            };
        }

        return $next;
    }

    public static function run()
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $requestMethod = strtolower($_SERVER['REQUEST_METHOD']);
        if ($requestUri !== '/') {
            $requestUri = rtrim($requestUri, '/');
        }
        foreach (self::$routes as $route) {
            if (preg_match($route->getCompiledPath(), $requestUri, $matches) && $route->getMethod() === $requestMethod) {
                $service = Service::getInstance();
                $request = $service->get(Request::class);

                $routeParamSegment = $route->getParamSegment();
                foreach ($routeParamSegment as $param) {
                    $request->setParam($param, $matches[$param]);
                }
                $pipeline = self::buildPipeline($route->getMiddlewares(), $route->getHandler());
                $pipeline();
                $session = $service->get(Session::class);
                $session->getFlashBag()->set('old_url', $request->getUri());
                return;
            }
        }

        // If no route matched
        http_response_code(404);
        echo "404 Not Found";
    }
}
