<?php

declare(strict_types=1);

use Slim\App;

return function (App $app): void {
    $app->addErrorMiddleware(
        getenv('APP_ENV') === 'dev',
        true,
        true
    );
    $app->addRoutingMiddleware();
};
