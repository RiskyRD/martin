<?php

use App\Model\ProductModel;
use App\Model\TransactionModel;
use App\Model\UserModel;
use Core\Auth\User;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;

use function DI\autowire;

use Core\Database\DB;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        UserModel::class => function (ContainerInterface $c) {
            return new UserModel($c->get(DB::class));
        },
        ProductModel::class => function (ContainerInterface $c) {
            return new ProductModel($c->get(DB::class));
        },
        TransactionModel::class => autowire(TransactionModel::class),
        User::class => autowire(UserModel::class),
    ]);
};
