<?php
session_save_path(__DIR__ . '/../storage/sessions');
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
require_once __DIR__ . '/../src/route/web.php';

use Core\Route\AppRoute;



AppRoute::run();
