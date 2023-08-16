<?php

namespace App\Factory;

use Psr\Http\Message\ServerRequestInterface;
use Slim\Factory\ServerRequestCreatorFactory;

class ServerRequestFactory
{
    /** @SuppressWarnings(PHPMD.StaticAccess) */
    public function __invoke(): ServerRequestInterface
    {
        $serverRequestCreator = ServerRequestCreatorFactory::create();
        $request = $serverRequestCreator->createServerRequestFromGlobals();

        return $request;
    }
}
