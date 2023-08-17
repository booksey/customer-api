<?php

use App\Factory\ServerRequestFactory;
use App\Interfaces\DatabaseServiceInterface;
use App\Services\DatabaseService;
use Psr\Http\Message\ServerRequestInterface;

use function DI\factory;

return [
    ServerRequestInterface::class => factory(ServerRequestFactory::class),
    DatabaseServiceInterface::class => function () {
        return new DatabaseService();
    }
];
