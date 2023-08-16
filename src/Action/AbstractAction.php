<?php

namespace App\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

abstract class AbstractAction
{
    protected ServerRequestInterface $request;
    protected ResponseInterface $response;

    final public function __invoke(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->request = $request;
        $this->response = $response;
        return $this->invoke();
    }

    abstract protected function invoke(): ResponseInterface;
}
