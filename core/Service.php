<?php

namespace Core;

use Core\Auth\Auth;
use Core\Database\DB;
use Core\HttpFoundation\Redirect;
use Core\HttpFoundation\Request;
use DI\ContainerBuilder;
use Core\View\Render;
use DI\Container;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;


class Service
{
    protected  Container $container;

    protected static ?Service $instance = null;

    private function __construct()
    {
        $containerBuilder = new ContainerBuilder();
        $containerBuilder->addDefinitions([
            Render::class => function (ContainerInterface $c) {
                return new Render($c->get(Session::class), $c->get(Auth::class));
            },
            DB::class => function (ContainerInterface $c) {
                return DB::getInstance();
            },
            Session::class => function (ContainerInterface $c) {
                $session = new Session();
                $session->start();
                return $session;
            },
            Request::class => function (ContainerInterface $c) {
                $request = Request::createFromGlobals();
                $request->setSession($c->get(Session::class));
                return $request;
            },
            Redirect::class => function (ContainerInterface $c) {
                return new Redirect(
                    $c->get(Request::class),
                    $c->get(Session::class)
                );
            },
            Auth::class => function (ContainerInterface $c) {
                return new Auth(
                    $c->get(Session::class),
                    $c->get(\Core\Auth\User::class),
                    $c->get(Request::class)
                );
            },
        ]);
        $modelBootstrap = require __DIR__ . '/../src/Model/bootstrap.php';
        $modelBootstrap($containerBuilder);
        $this->container = $containerBuilder->build();
    }

    public function call(array $callback)
    {
        // $object = $this->container->get($callback[0]);
        return $this->container->call($callback);
    }

    public function get(string $id)
    {
        return $this->container->get($id);
    }

    public static function getInstance(): Service
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
