<?php

declare(strict_types=1);

namespace App\Action\Api\Customer;

use App\Action\AbstractAction;
use App\Collections\CustomerCollection;
use App\Entity\Customer;
use App\Interfaces\DatabaseServiceInterface;
use DateTime;
use InvalidArgumentException;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use Webmozart\Assert\Assert;

class UpdateAction extends AbstractAction
{
    public function __construct(private readonly DatabaseServiceInterface $databaseService)
    {
    }

    public function invoke(): ResponseInterface
    {
        $parsedBody = $this->request->getParsedBody();
        if (!is_object($parsedBody) || !is_array($parsedBody->customers) || count($parsedBody->customers) < 1) {
            return new JsonResponse(['success' => false], 500);
        }

        $customerCollection = new CustomerCollection();
        foreach ($parsedBody->customers as $customerArray) {
            try {
                Assert::integer($customerArray['id'], 'Invalid customer id.');
                Assert::stringNotEmpty($customerArray['name'], 'Invalid customer name.');
                Assert::stringNotEmpty($customerArray['address'], 'Invalid customer address.');
                Assert::nullOrString($customerArray['code'], 'Invalid customer code.');
                Assert::regex($customerArray['contractDate'], '/^[0-9]{4}-[0-9]{2}-[0-9]{2}/', 'Invalid contract date.');
            } catch (InvalidArgumentException $e) {
                return new JsonResponse(['success' => false], 500);
            }

            $customer = new Customer(
                $customerArray['id'],
                $customerArray['name'],
                $customerArray['address'],
                $customerArray['code'],
                DateTime::createFromFormat('Y-m-d', $customerArray['contractDate'])
            );
            $customerCollection->add($customer);
        }

        $result = $this->databaseService->update($customerCollection);

        return new JsonResponse(['success' => $result], $result ? 200 : 500);
    }
}
