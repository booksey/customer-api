<?php

declare(strict_types=1);

namespace App\Test\Action\Api\Customer;

use App\Action\Api\Customer\GetAction;
use App\Collections\CustomerCollection;
use App\Test\Action\AbstractActionTest;
use Laminas\Diactoros\Response\JsonResponse;

class GetActionTest extends AbstractActionTest
{

    /**
     * @dataProvider invokeDataProvider
     */
    public function testInvoke(mixed $parsedBody, int $expectedCode, mixed $expectedPayloadType): void
    {
        $this->request->expects($this->once())->method('getParsedBody')->willReturn($parsedBody);

        $action = new GetAction($this->databaseService);
        /** @var JsonResponse $response */
        $response = $action($this->request, $this->response);
        $payload = $response->getPayload();
        $payloadData = $payload['data'];

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($expectedCode, $response->getStatusCode());

        if (is_null($expectedPayloadType)) {
            $this->assertNull($payloadData);
        }
        if (!is_null($expectedPayloadType)) {
            $this->assertInstanceOf($expectedPayloadType, $payloadData);
        }
    }

    public static function invokeDataProvider(): array
    {
        return [
            [null, 500, null],  //invalid parsedBody
            [(object)['customerId' => null], 200, CustomerCollection::class],  //OK, get all Customers
            [(object)['invalidProperty' => 1], 200, CustomerCollection::class],  //OK, ignores property and gets all Customers
            [(object)['customerId' => 9999], 200, null],  //OK, not found user
        ];
    }
}
