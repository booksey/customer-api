<?php

namespace App\Test\Action;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;

class AbstractActionTest extends TestCase
{
    protected ResponseInterface|MockObject $response;
    protected ServerRequestInterface|MockObject $request;

    protected function setUp(): void
    {
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
    }

    final public function writeBodyOnce(): StreamInterface
    {
        $body = $this->createMock(StreamInterface::class);
        $body->expects($this->once())->method('write');

        return $body;
    }
}
