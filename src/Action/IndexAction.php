<?php

declare(strict_types=1);

namespace App\Action;

use App\Action\AbstractAction;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;

class IndexAction extends AbstractAction
{
    public function invoke(): ResponseInterface
    {
        return new JsonResponse([], 200);
    }
}
