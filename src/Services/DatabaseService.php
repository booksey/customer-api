<?php

namespace App\Services;

use App\Entity\Customer;
use App\Collections\CustomerCollection;
use App\Interfaces\DatabaseServiceInterface;
use Aws\DynamoDb\DynamoDbClient;
use Aws\DynamoDb\Marshaler;
use Aws\Result;
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
        /** @var DateTime $contractDate */
        $contractDate = DateTime::createFromFormat('Y-m-d', $item['contractDate']);
        return new Customer(
            $item['id'],
            $item['name'],
            $item['address'],
            $item['code'],
            $contractDate
        );
    }

    private function createItemFromCustomer(Customer $customer): array
    {
        /** @var string $customerString */
        $customerString = json_encode($customer);
        /** @var array $result */
        $result = json_decode($customerString, true);
        return $result;
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
            /** @var array $resultItem */
            $unmarshalledItem = $this->marshaler->unmarshalItem($resultItem);
            /** @var array $unmarshalledItem */
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
            /** @var array $item */
            $unmarshalledItem = $this->marshaler->unmarshalItem($item);
            /** @var array $unmarshalledItem */
            $customerCollection->add($this->createCustomerFromItem($unmarshalledItem));
        }

        if (count($customerCollection->customers) > 0) {
            return $customerCollection;
        }

        return null;
    }

    public function insert(CustomerCollection $customers): bool
    {
        $wasInsert = false;
        foreach ($customers as $customer) {
            /** @var Customer $customer */
            $customerItem = $this->createItemFromCustomer($customer);
            $storedCustomer = $this->select($customerItem['id']);

            if (is_null($storedCustomer)) {
                $marshalledItem = $this->marshaler->marshalItem($customerItem);
                $result = $this->client->putItem([
                    'Item' => $marshalledItem,
                    'TableName' => self::CUSTOMER_TABLE_NAME
                ]);

                /** @var Result $result */
                $metadata = $result->get('@metadata');
                $statusCode = is_array($metadata) && $metadata['statusCode'] ? $metadata['statusCode'] : 0;
                if ($statusCode === 200) {
                    $wasInsert = true;
                }
            }
        }

        return $wasInsert;
    }

    public function update(CustomerCollection $customers): bool
    {
        $wasUpdate = false;
        foreach ($customers as $customer) {
            /** @var Customer $customer */
            $customerItem = $this->createItemFromCustomer($customer);
            $storedCustomer = $this->select($customerItem['id']);

            if ($storedCustomer instanceof Customer) {
                $marshalledIdItem = $this->marshaler->marshalItem(['id' => $customerItem['id']]);
                unset($customerItem['id']);

                $attributeUpdates = [];
                foreach ($customerItem as $attrName => $attrValue) {
                    $attributeUpdates[$attrName] = [
                        'Action' => 'PUT',
                        'Value' => $this->marshaler->marshalValue($attrValue),
                    ];
                }

                $updateItem = [
                    'AttributeUpdates' => $attributeUpdates,
                    'Key' => $marshalledIdItem,
                    'TableName' => self::CUSTOMER_TABLE_NAME,
                ];
                /** @var Result $result */
                $result = $this->client->updateItem($updateItem);
                $metadata = $result->get('@metadata');
                $statusCode = is_array($metadata) && $metadata['statusCode'] ? $metadata['statusCode'] : 0;
                if ($statusCode === 200) {
                    $wasUpdate = true;
                }
            }
        }

        return $wasUpdate;
    }

    public function delete(CustomerCollection $customers): bool
    {
        $wasDelete = false;
        foreach ($customers as $customer) {
            /** @var Customer $customer */
            $customerItem = $this->createItemFromCustomer($customer);
            $storedCustomer = $this->select($customerItem['id']);

            if ($storedCustomer instanceof Customer) {
                $marshalledItem = $this->marshaler->marshalItem(['id' => $customerItem['id']]);
                $result = $this->client->deleteItem([
                    'Key' => $marshalledItem,
                    'TableName' => self::CUSTOMER_TABLE_NAME
                ]);
                $metadata = $result->get('@metadata');
                $statusCode = is_array($metadata) && $metadata['statusCode'] ? $metadata['statusCode'] : 0;
                if ($statusCode === 200) {
                    $wasDelete = true;
                }
            }
        }

        return $wasDelete;
    }
}
