<?php

use Core\Route\Router;
use App\Controller\LoginController;
use App\Controller\RegisterController;
use App\Controller\HomeController;
use App\Controller\ProductController;
use App\Controller\ReportController;
use App\Controller\TransactionController;
use App\Controller\UserController;
use Core\Route\Route;

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


Router::get('/user/create', [UserController::class, 'userCreateView']);
Router::get('/user', [UserController::class, 'listUsers']);
Router::post('/users/create', [UserController::class, 'userCreate']);
Router::get('/user/{id}/update', [UserController::class, 'updateUserView']);
Router::post('/user/{id}/update', [UserController::class, 'userUpdate']);
Router::post('/user/{id}/delete', [UserController::class, 'deleteUser']);
// Router::post('/users/update', [UserController::class, 'userUpdate']);
