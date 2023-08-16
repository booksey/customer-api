<?php

declare(strict_types=1);

namespace App\Action\Api\User;

use App\Action\AbstractAction;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;

class CreateAction extends AbstractAction
{
    public function invoke(): ResponseInterface
    {
        $parsedBody = $this->request->getParsedBody();
        return new JsonResponse(['data' => []], 500);
    }
}
