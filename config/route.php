<?php

declare(strict_types=1);

use Slim\App as SlimApp;
use Slim\Interfaces\RouteCollectorProxyInterface;

return function (SlimApp $app): void {
    /** Index group */
    $app->get('[/]', \App\Action\IndexAction::class)->setName('index');
    $app->group('/api/user', function (RouteCollectorProxyInterface $app) {
        $app->get('/get', \App\Action\Api\User\GetAction::class)->setName('user:get');
        $app->get('/create', \App\Action\Api\User\CreateAction::class)->setName('user:create');
        $app->get('/update', \App\Action\Api\User\UpdateAction::class)->setName('user:update');
        $app->get('/delete', \App\Action\Api\User\DeleteAction::class)->setName('user:delete');
    });
};
