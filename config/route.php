<?php

declare(strict_types=1);

use Slim\App as SlimApp;
use Slim\Interfaces\RouteCollectorProxyInterface;

return function (SlimApp $app): void {
    /** Index group */
    $app->get('[/]', \App\Action\IndexAction::class)->setName('index');
    $app->group('/api/customer', function (RouteCollectorProxyInterface $app) {
        $app->post('/get', \App\Action\Api\Customer\GetAction::class)->setName('customer:get');
        $app->post('/create', \App\Action\Api\Customer\CreateAction::class)->setName('customer:create');
        $app->post('/update', \App\Action\Api\Customer\UpdateAction::class)->setName('customer:update');
        $app->post('/delete', \App\Action\Api\Customer\DeleteAction::class)->setName('customer:delete');
    });
};
