<?php

declare(strict_types=1);

namespace App\Test\Action\Api\User;

use App\Action\Api\User\CreateAction;
use App\Test\Action\AbstractActionTest;
use DateTime;
use Laminas\Diactoros\Response\JsonResponse;

class CreateActionTest extends AbstractActionTest
{
    /**
     * @dataProvider invokeDataProvider
     */
    public function testInvoke(?object $parsedBody, ?int $expectedCode): void
    {
        $this->request->expects($this->once())->method('getParsedBody')->willReturn($parsedBody);

        $action = new CreateAction();
        $response = $action($this->request, $this->response);

        /** @var JsonResponse $response */
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals($expectedCode, $response->getStatusCode());
    }

    public static function invokeDataProvider(): array
    {
        return [
            [null, 500],  //Invalid userData
            [(object)['userData' => json_encode(['invalidProperty' => 1])], 500],  //Invalid userData
            [(object)[
                'userData' => json_encode([
                    'name' => 'test name',
                    'address' => 'test address',
                    'code' => 'test code',
                    'contractDate' => new DateTime()
                ])
            ], 200],  //OK
        ];
    }
}
