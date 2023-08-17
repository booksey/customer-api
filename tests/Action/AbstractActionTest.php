<?php

namespace App\Test\Action;

use App\Interfaces\DatabaseServiceInterface;
use App\Services\DatabaseService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class AbstractActionTest extends TestCase
{
    protected MockObject $response;
    protected MockObject $request;
    protected DatabaseServiceInterface $databaseService;

    protected function setUp(): void
    {
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->response = $this->createMock(ResponseInterface::class);
        $this->databaseService = new DatabaseService();
    }
}
