<?php

use Core\Route\Router;
use App\Controller\LoginController;
use App\Controller\RegisterController;
use App\Controller\HomeController;
use App\Controller\ProductController;
use App\Controller\ReportController;
use App\Controller\TransactionController;
use App\Controller\UserController;

Router::get('/register', [RegisterController::class, 'registerPage']);
Router::get('/login', [LoginController::class, 'loginPage']);
Router::post('/login', [LoginController::class, 'loginUser']);
Router::post('/register', [RegisterController::class, 'registerUser']);
Router::get('/', [HomeController::class, 'index']);
Router::post('/param-tester', [HomeController::class, 'paramTester'])->middleware([App\Middleware\CsrfValid::class]);
Router::get('/form-test', [HomeController::class, 'paramForm']);

Router::get('/products', [ProductController::class, 'listProducts']);
Router::post('/products/add', [ProductController::class, 'addProduct']);

Router::get('/transaction', [TransactionController::class, 'transaction']);


Router::get('/report', [ReportController::class, 'generateReport']);


Router::get('/user', [UserController::class, 'user']);
