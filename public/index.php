<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
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

    try {
        $routeDispatcher = require 'config/route.php';
        $routeDispatcher($app);
        $middlewareDispatcher = require 'config/middleware.php';
        $middlewareDispatcher($app, $router);
        /** @var ServerRequestInterface $request */
        $request = $container->get(ServerRequestInterface::class);

        $app->run($request);
    } catch (HttpNotFoundException $e) {
        echo json_encode(['success' => false, 'data' => [], 'message' => 'Invalid route.']);
        exit;
    }
})(require 'config/container.php');
