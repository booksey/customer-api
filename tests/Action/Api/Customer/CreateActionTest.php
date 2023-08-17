<?php

declare(strict_types=1);

namespace App\Test\Action\Api\Customer;

use App\Action\Api\Customer\CreateAction;
use App\Action\Api\Customer\CreateActionAction;
use App\Entity\Customer;
use App\Test\Action\AbstractActionTest;
use DateTime;
use InvalidArgumentException;
use Laminas\Diactoros\Response\JsonResponse;

class CreateActionTest extends AbstractActionTest
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
        $action = new CreateAction($this->databaseService);

        /** @var JsonResponse $response */
        $response = $action($this->request, $this->response);

        $payload = $response->getPayload();
        $payloadResult = $payload['success'];

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($expectedCode, $response->getStatusCode());
        $this->assertEquals($expectedResult, $payloadResult);
    }

    public static function invokeDataProvider(): array
    {
        $invalidCustomer = ['id' => 'hejho'];
        $newCustomer = [
            'id' => intval(microtime(true)),
            'name' => 'Test Name',
            'address' => 'Test address',
            'code' => 'Test Code',
            'contractDate' => date('Y-m-d')
        ];
        $newCustomer2 = [
            'id' => intval(microtime(true)) + 1,
            'name' => 'Test Name',
            'address' => 'Test address',
            'code' => 'Test Code',
            'contractDate' => date('Y-m-d')
        ];
        $newCustomer3 = [
            'id' => intval(microtime(true)) + 2,
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
            [(object) ['customers' => [$newCustomer]], 200, true],  //OK
            [(object) ['customers' => [$existingCustomer]], 500, false],  //existing customer
            [(object) ['customers' => [$newCustomer, $newCustomer2]], 200, true],  //2 customer inserted
            [(object) ['customers' => [$existingCustomer, $existingCustomer]], 500, false],  //customers already exists
            [(object) ['customers' => [$newCustomer3, $existingCustomer]], 200, true],  //new created, existing ignored
        ];
    }
}
