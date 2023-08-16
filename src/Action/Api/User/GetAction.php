<?php

declare(strict_types=1);

namespace App\Action\Api\User;

use App\Action\AbstractAction;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;

class GetAction extends AbstractAction
{
    public function invoke(): ResponseInterface
    {
        $parsedBody = $this->request->getParsedBody();
        $customerId = isset($parsedBody->customerId) && is_numeric($parsedBody->customerId)
            ? $parsedBody->customerId
            : null;

        if (!is_null($customerId) && intval($customerId) === 0) {
            return new JsonResponse(['success' => false], 500);
        }

        return new JsonResponse([
            'data' => [],
        ], 200);
    }
}
