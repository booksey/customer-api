<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Dotenv\Dotenv;
use Psr\Container\ContainerInterface;

return (function (): ContainerInterface {
    $dotenv = Dotenv::createUnsafeImmutable(strval(getcwd()));
    $dotenv->load();

    $containerBuilder = new ContainerBuilder();
    $containerBuilder->addDefinitions(require 'config/dependencies.php');
    $configs = require 'config/config.php';

    $container = $containerBuilder->build();
    $container->set('config', $configs);
    $container->set(ContainerInterface::class, $container);

    return $container;
})();
