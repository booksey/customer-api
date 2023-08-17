<?php

declare(strict_types=1);

namespace App\Test\Action\Api\Customer;

use App\Action\Api\Customer\DeleteAction;
use App\Test\Action\AbstractActionTest;
use Laminas\Diactoros\Response\JsonResponse;

class DeleteActionTest extends AbstractActionTest
{
    /**
     * @dataProvider invokeDataProvider
     */
    public function testInvoke(?object $parsedBody, ?int $expectedCode): void
    {
        $this->request->expects($this->once())->method('getParsedBody')->willReturn($parsedBody);

        $action = new DeleteAction();
        $response = $action($this->request, $this->response);

        /** @var JsonResponse $response */
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($expectedCode, $response->getStatusCode());
    }

    public static function invokeDataProvider(): array
    {
        return [
            [null, 500],  //Invalid customerId
            [(object)['customerId' => 0], 500],  //Invalid customerId
            [(object)['customerId' => 1], 200],  //OK
        ];
    }
}
