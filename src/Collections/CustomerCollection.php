<?php

declare(strict_types=1);

namespace App\Collections;

use App\Entity\Customer;
use ArrayIterator;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

final class CustomerCollection implements IteratorAggregate, JsonSerializable
{
    public array $customers;

    public function __construct(Customer ...$customer)
    {
        $this->customers = $customer;
    }

    public function add(Customer $customer): void
    {
        $this->customers[] = $customer;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->customers);
    }

    public function jsonSerialize(): array
    {
        return $this->customers;
    }
}
