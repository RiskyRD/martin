<?php

use App\Model\UserModel;
use Core\Auth\User;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;

use function DI\autowire;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        UserModel::class => function (ContainerInterface $c) {
            return new UserModel($c->get(Core\Database\DB::class));
        },
        User::class => autowire(UserModel::class),
    ]);
};
