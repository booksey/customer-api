<?php

namespace App\Services;

use App\Entity\Customer;
use App\Collections\CustomerCollection;
use App\Interfaces\DatabaseServiceInterface;
use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;
use Aws\Sdk;
use DateTime;
use Dotenv\Dotenv;
use RuntimeException;
use Throwable;

class DatabaseService implements DatabaseServiceInterface
{
    private readonly DynamoDbClient $client;
    private readonly Marshaler $marshaler;
    private const CUSTOMER_TABLE_NAME = 'Customer';

    public function __construct()
    {
        $dotenv = Dotenv::createUnsafeImmutable(strval(getcwd()));
        $dotenv->load();
        try {
            $sdk = new Sdk([
                'region'   => 'eu-north-1',
                'version'  => 'latest',
                'credentials' => [
                    'key'    => getenv('AWS_ACCESS_KEY_ID'),
                    'secret' => getenv('YOUR_AWS_SECRET_ACCESS_KEY'),
                ],
                'DynamoDb' => [
                    'region' => 'eu-north-1'
                ]
            ]);
            $this->client = $sdk->createDynamoDb();
            $this->marshaler = new Marshaler();
        } catch (Throwable $error) {
            throw new RuntimeException($error->getMessage());
        }
    }

    private function createCustomerFromItem(array $item): Customer
    {
        return new Customer(
            $item['id'],
            $item['name'],
            $item['address'],
            $item['code'],
            DateTime::createFromFormat('Y-m-d', $item['contractDate'])
        );
    }

    public function select(?int $customerId): null|Customer|CustomerCollection
    {
        if ($customerId || $customerId === 0) {
            $marshalledItem = $this->marshaler->marshalItem(['id' => $customerId]);
            $result = $this->client->getItem([
                'Key' => $marshalledItem,
                'TableName' => self::CUSTOMER_TABLE_NAME
            ]);

            $resultItem = $result->get('Item');

            if (!$resultItem) {
                return null;
            }
            $unmarshalledItem = $this->marshaler->unmarshalItem($resultItem);
            return $this->createCustomerFromItem($unmarshalledItem);
        }

        $iterator = $this->client->getIterator(
            'Scan',
            [
                'TableName' => self::CUSTOMER_TABLE_NAME,
                'Select' => 'ALL_ATTRIBUTES'
            ]
        );

        $customerCollection = new CustomerCollection();
        foreach ($iterator as $item) {

            $unmarshalledItem = $this->marshaler->unmarshalItem($item);
            $customerCollection->add($this->createCustomerFromItem($unmarshalledItem));
        }

        if (count($customerCollection->customers) > 0) {
            return $customerCollection;
        }

        return null;
    }

    public function insert(Customer|CustomerCollection $data): bool
    {
        return false;
    }

    public function update(Customer|CustomerCollection $data): bool
    {
        return false;
    }

    public function delete(Customer|CustomerCollection $data): bool
    {
        return false;
    }
}
