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

Router::get('/login', [LoginController::class, 'loginPage']);
Router::post('/login', [LoginController::class, 'loginUser']);
Router::get('/', [HomeController::class, 'index']);
Router::post('/param-tester', [HomeController::class, 'paramTester'])->middleware([App\Middleware\CsrfValid::class]);
Router::get('/form-test', [HomeController::class, 'paramForm']);

Router::get('/products', [ProductController::class, 'listProducts']);
Router::get('/products/create', [ProductController::class, 'createProductView']);
Router::get('/products/{id}/update', [ProductController::class, 'updateProductView']);
Router::post('/products/add', [ProductController::class, 'addProduct']);
Router::post('/products/{id}/update', [ProductController::class, 'updateProduct']);
Router::post('/products/{id}/delete', [ProductController::class, 'deleteProduct']);

Router::get('/transaction', [TransactionController::class, 'transaction']);
Router::get('/transaction/create', [TransactionController::class, 'createTransactionView']);
Router::post('/transaction/create', [TransactionController::class, 'createTransaction']);
Router::get('/transaction/{id}/create', [TransactionController::class, 'createTransactionWithIdView']);
Router::post('/transaction/{id}/create', [TransactionController::class, 'createTransactionWithId']);
Router::post('/transaction/{id}/delete/detail', [TransactionController::class, 'deleteTransactionDetails']);

Router::post('/generateReport', [ReportController::class, 'generateReport']);
Router::get('/report', [ReportController::class, 'report']);

// Router::get('/report/create', [ReportController::class, 'generateReport']);
// Router::get('/report/details', [ReportController::class, 'reportDetails']);


Router::get('/user/create', [UserController::class, 'userCreateView']);
Router::get('/user', [UserController::class, 'listUsers']);
Router::post('/users/create', [UserController::class, 'userCreate']);
Router::get('/user/{id}/update', [UserController::class, 'updateUserView']);
Router::post('/user/{id}/update', [UserController::class, 'userUpdate']);
Router::post('/user/{id}/delete', [UserController::class, 'deleteUser']);

// Router::post('/users/update', [UserController::class, 'userUpdate']);
