<?php

declare(strict_types=1);

namespace App\Test\Action\Api\Customer;

use App\Action\Api\Customer\UpdateAction;
use App\Test\Action\AbstractActionTest;
use Laminas\Diactoros\Response\JsonResponse;

class UpdateActionTest extends AbstractActionTest
{
    /**
     * @dataProvider invokeDataProvider
     */
    public function testInvoke(?object $parsedBody, ?int $expectedCode): void
    {
        $this->request->expects($this->once())->method('getParsedBody')->willReturn($parsedBody);

        $action = new UpdateAction();
        $response = $action($this->request, $this->response);

        /** @var JsonResponse $response */
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($expectedCode, $response->getStatusCode());
    }

    public static function invokeDataProvider(): array
    {
        return [
            [null, 500],  //Invalid customerId
            [(object)['customerId' => 0, 'CustomerData' => []], 500],  //Invalid customerId and CustomerData
            [
                (object)['customerId' => 0, 'CustomerData' => json_encode(['address' => 'test address'])], 500
            ],  //Invalid customerId
            [
                (object)['customerId' => 1, 'CustomerData' => json_encode(['invalidProperty' => 'test address'])], 500
            ],  //Invalid property
            [
                (object)['customerId' => 1, 'CustomerData' => json_encode(['name' => 'test name', 'code' => null])], 200
            ],  //OK
        ];
    }
}
