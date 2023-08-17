<?php

declare(strict_types=1);

namespace App\Action\Api\Customer;

use App\Action\AbstractAction;
use App\Interfaces\DatabaseServiceInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;

class GetAction extends AbstractAction
{
    public function __construct(private readonly DatabaseServiceInterface $databaseService)
    {
    }

    public function invoke(): ResponseInterface
    {
        $parsedBody = $this->request->getParsedBody();
        if (!is_object($parsedBody)) {
            return new JsonResponse(['data' => null], 500);
        }

        $customerId = isset($parsedBody->customerId) && is_numeric($parsedBody->customerId)
            ? intval($parsedBody->customerId)
            : null;

        $result = $this->databaseService->select($customerId);

        return new JsonResponse(['data' => $result], 200);
    }
}
