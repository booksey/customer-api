<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Factory\AppFactory;

chdir(dirname(__DIR__));
require_once 'vendor/autoload.php';

(function (ContainerInterface $container): void {
    $app = AppFactory::createFromContainer($container);
    if (getenv('APP_ENV') == 'dev') {
        $app->setBasePath((function () {
            return dirname($_SERVER['SCRIPT_NAME']);
        })());
    }

    $router = $app->getRouteCollector();

    $routeDispatcher = require 'config/route.php';
    $routeDispatcher($app);

    $middlewareDispatcher = require 'config/middleware.php';
    $middlewareDispatcher($app, $router);

    /** @var ServerRequestInterface $request */
    $request = $container->get(ServerRequestInterface::class);

    $app->run($request);
})(require 'config/container.php');
