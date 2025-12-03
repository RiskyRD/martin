<?php
require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use Symfony\Component\Console\Application;

$application = new Application();
$service = Core\Service::getInstance();
$application->add($service->get(App\Console\AssetSymlink::class));
$application->add($service->get(App\Console\ExecuteDDL::class));
$application->add($service->get(App\Console\DropDDL::class));
$application->add($service->get(App\Console\AddAdmin::class));

$application->run();
