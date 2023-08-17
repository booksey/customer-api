<?php

declare(strict_types=1);

use Slim\App as SlimApp;
use Slim\Interfaces\RouteCollectorProxyInterface;

return function (SlimApp $app): void {
    /** Index group */
    $app->get('[/]', \App\Action\IndexAction::class)->setName('index');
    $app->group('/api/Customer', function (RouteCollectorProxyInterface $app) {
        $app->get('/get', \App\Action\Api\Customer\GetAction::class)->setName('Customer:get');
        $app->get('/create', \App\Action\Api\Customer\CreateAction::class)->setName('Customer:create');
        $app->get('/update', \App\Action\Api\Customer\UpdateAction::class)->setName('Customer:update');
        $app->get('/delete', \App\Action\Api\Customer\DeleteAction::class)->setName('Customer:delete');
    });
};
