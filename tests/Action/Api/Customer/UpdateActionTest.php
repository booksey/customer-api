<?php

declare(strict_types=1);

namespace App\Test\Action\Api\Customer;

use App\Action\Api\Customer\UpdateAction;
use App\Entity\Customer;
use App\Test\Action\AbstractActionTest;
use DateTime;
use InvalidArgumentException;
use Laminas\Diactoros\Response\JsonResponse;

class UpdateActionTest extends AbstractActionTest
{
    /**
     * @dataProvider invokeDataProvider
     */
    public function testInvoke(
        mixed $parsedBody,
        int $expectedCode,
        mixed $expectedResult
    ): void {
        $this->request->expects($this->once())->method('getParsedBody')->willReturn($parsedBody);
        $action = new UpdateAction($this->databaseService);

        /** @var JsonResponse $response */
        $response = $action($this->request, $this->response);

        /** @var array $payload */
        $payload = $response->getPayload();
        $payloadResult = $payload['success'];

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($expectedCode, $response->getStatusCode());
        $this->assertEquals($expectedResult, $payloadResult);
    }

    public static function invokeDataProvider(): array
    {
        $invalidCustomer = ['id' => 'hejho'];
        $validCustomer = [
            'id' => intval(microtime(true)),
            'name' => 'Test Name',
            'address' => 'Test address',
            'code' => 'Test Code',
            'contractDate' => date('Y-m-d')
        ];
        $existingCustomer = [
            'id' => 0,
            'name' => 'Test Name',
            'address' => 'Test address',
            'code' => 'Test Code',
            'contractDate' => date('Y-m-d')
        ];
        return [
            [null, 500, false, null],  //invalid parsedBody
            [(object) ['customers' => [$invalidCustomer]], 500, false],  //invalid customer
            [(object) ['customers' => [$validCustomer]], 500, false],  //valid, but non-existent customer
            [(object) ['customers' => [$existingCustomer]], 200, true],  //OK
            [(object) ['customers' => [$existingCustomer, $existingCustomer]], 200, true],  //OK, 2 customer update
            [(object) ['customers' => [$existingCustomer, $invalidCustomer]], 500, false],  //there was an error
        ];
    }
}
