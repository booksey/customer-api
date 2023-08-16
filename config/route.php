<?php

declare(strict_types=1);

use Slim\App as SlimApp;
use Slim\Interfaces\RouteCollectorProxyInterface;

return function (SlimApp $app): void {
    /** Index group */
    $app->get('[/]', \App\Action\IndexAction::class)->setName('index');
    $app->group('/api', function (RouteCollectorProxyInterface $app) {
    });
};
