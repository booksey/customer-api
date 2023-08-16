<?php

declare(strict_types=1);

namespace App\Action\Api\User;

use App\Action\AbstractAction;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;

class UpdateAction extends AbstractAction
{
    public function invoke(): ResponseInterface
    {
        return new JsonResponse([
            'success' => false,
            'data' => [],
            'message' => ''
        ], 500);
    }
}
