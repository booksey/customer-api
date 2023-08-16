<?php

use App\Factory\SerializerFactory;
use App\Factory\ServerRequestFactory;
use JMS\Serializer\SerializerInterface;
use Psr\Http\Message\ServerRequestInterface;

use function DI\factory;

return [
    SerializerInterface::class => factory(SerializerFactory::class),
    ServerRequestInterface::class => factory(ServerRequestFactory::class),
];
