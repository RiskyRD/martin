<?php

use Core\Route\Router;
use App\Controller\LoginController;
use App\Controller\RegisterController;
use App\Controller\HomeController;
use App\Controller\ProductController;
use App\Controller\ReportController;
use App\Controller\TransactionController;
use App\Controller\UserController;
use App\Middleware\AuthMiddleware;
use App\Middleware\IsAdminMiddleware;
use Core\Route\Route;

Router::get('/login', [LoginController::class, 'loginPage']);
Router::post('/login', [LoginController::class, 'loginUser']);
Router::get('/', [HomeController::class, 'index']);
Router::post('/logout', [LoginController::class, 'logoutUser']);

Router::middleware([AuthMiddleware::class])->get('/products', [ProductController::class, 'listProducts']);
Router::middleware([AuthMiddleware::class])->get('/products/create', [ProductController::class, 'createProductView']);
Router::middleware([AuthMiddleware::class])->get('/products/{id}/update', [ProductController::class, 'updateProductView']);
Router::middleware([AuthMiddleware::class])->post('/products/add', [ProductController::class, 'addProduct']);
Router::middleware([AuthMiddleware::class])->post('/products/{id}/update', [ProductController::class, 'updateProduct']);
Router::middleware([AuthMiddleware::class])->post('/products/{id}/delete', [ProductController::class, 'deleteProduct']);

Router::middleware([AuthMiddleware::class])->get('/transaction', [TransactionController::class, 'transaction']);
Router::middleware([AuthMiddleware::class])->get('/transaction/create', [TransactionController::class, 'createTransactionView']);
Router::middleware([AuthMiddleware::class])->post('/transaction/create', [TransactionController::class, 'createTransaction']);
Router::middleware([AuthMiddleware::class])->get('/transaction/{id}/create', [TransactionController::class, 'createTransactionWithIdView']);
Router::middleware([AuthMiddleware::class])->post('/transaction/{id}/create', [TransactionController::class, 'createTransactionWithId']);
Router::middleware([AuthMiddleware::class])->post('/transaction/{id}/delete/detail', [TransactionController::class, 'deleteTransactionDetails']);

Router::middleware([AuthMiddleware::class])->post('/generateReport', [ReportController::class, 'generateReport']);
Router::middleware([AuthMiddleware::class])->get('/report', [ReportController::class, 'report']);


Router::middleware([AuthMiddleware::class])->get('/user', [UserController::class, 'listUsers']);
Router::middleware([AuthMiddleware::class, IsAdminMiddleware::class])->get('/user/create', [UserController::class, 'userCreateView']);
Router::middleware([AuthMiddleware::class, IsAdminMiddleware::class])->post('/users/create', [UserController::class, 'userCreate']);
Router::middleware([AuthMiddleware::class, IsAdminMiddleware::class])->get('/user/{id}/update', [UserController::class, 'updateUserView']);
Router::middleware([AuthMiddleware::class, IsAdminMiddleware::class])->post('/user/{id}/update', [UserController::class, 'userUpdate']);
Router::middleware([AuthMiddleware::class, IsAdminMiddleware::class])->post('/user/{id}/delete', [UserController::class, 'deleteUser']);
