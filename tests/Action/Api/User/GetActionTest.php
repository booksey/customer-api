<?php

declare(strict_types=1);

namespace App\Test\Action\Api\User;

use App\Action\Api\User\GetAction;
use App\Test\Action\AbstractActionTest;
use Laminas\Diactoros\Response\JsonResponse;

class GetActionTest extends AbstractActionTest
{
    /**
     * @dataProvider invokeDataProvider
     */
    public function testInvoke(?object $parsedBody, ?int $expectedCode): void
    {
        $this->request->expects($this->once())->method('getParsedBody')->willReturn($parsedBody);

        $action = new GetAction();
        $response = $action($this->request, $this->response);

        /** @var JsonResponse $response */
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($expectedCode, $response->getStatusCode());
    }

    public static function invokeDataProvider(): array
    {
        return [
            [null, 200],  //OK, get all customers
            [(object)['customerId' => 0], 500],  //invalid customerId
            [(object)['invalidProperty' => 1], 200],  //OK, ignores property and gets all customers
            [(object)['customerId' => 1], 200],  //OK, get one customer
        ];
    }
}
