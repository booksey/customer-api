<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Slim\App;

return function (App $app): void {
    /** @var ContainerInterface $container */

    $app->addErrorMiddleware(
        getenv('APP_ENV') === 'dev',
        true,
        true
    );
    $app->addRoutingMiddleware();
};
