<?php

namespace Core\View;

use Core\Auth\Auth;
use Symfony\Component\HttpFoundation\Session\Session;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Render
{
    protected Environment $twig;

    public function __construct(Session $session, Auth $auth)
    {
        $loader = new FilesystemLoader(__DIR__ . '/../../views');
        $this->twig = new Environment($loader, [
            'debug' => true,
        ]);

        $this->twig->addExtension(new \Twig\Extension\DebugExtension());

        // Make session globally available
        $this->twig->addGlobal('app', ['session' => $session]);

        // Make currentUser globally available
        $this->twig->addGlobal('currentUser', $auth->getCurrentUser());

        // CSRF token function
        $this->twig->addFunction(new \Twig\TwigFunction('csrf_token', function () use ($auth) {
            return $auth->getCsrfToken();
        }));
    }

    public function render(string $template, array $data = []): void
    {
        echo $this->twig->render($template, $data);
    }
}