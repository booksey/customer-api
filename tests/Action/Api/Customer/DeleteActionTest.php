<?php

declare(strict_types=1);

namespace App\Test\Action\Api\Customer;

use App\Action\Api\Customer\DeleteAction;
use App\Entity\Customer;
use App\Test\Action\AbstractActionTest;
use DateTime;
use InvalidArgumentException;
use Laminas\Diactoros\Response\JsonResponse;

class DeleteActionTest extends AbstractActionTest
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
        $action = new DeleteAction($this->databaseService);

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
        $existingCustomer = [
            'id' => 0,
            'name' => 'Test Name',
            'address' => 'Test address',
            'code' => 'Test Code',
            'contractDate' => date('Y-m-d')
        ];
        $existingCustomer2 = [
            'id' => 1,
            'name' => 'Test Name',
            'address' => 'Test address',
            'code' => 'Test Code',
            'contractDate' => date('Y-m-d')
        ];
        $validCustomer = [
            'id' => intval(microtime(true)),
            'name' => 'Test Name',
            'address' => 'Test address',
            'code' => 'Test Code',
            'contractDate' => date('Y-m-d')
        ];
        return [
            [null, 500, false, null],  //invalid parsedBody
            [(object) ['customers' => [$invalidCustomer]], 500, false],  //invalid customer
            [(object) ['customers' => [$validCustomer]], 500, false],  //non-existent customer
            [(object) ['customers' => [$existingCustomer]], 200, true],  //OK
            [(object) ['customers' => [$existingCustomer, $existingCustomer2]], 200, true],  //all delete OK
        ];
    }
}
