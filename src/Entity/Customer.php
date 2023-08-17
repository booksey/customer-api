<?php

namespace App\Entity;

use DateTime;
use JsonSerializable;

class Customer implements JsonSerializable
{
    public function __construct(
        private ?int $id,
        private string $name,
        private string $address,
        private ?string $code,
        private DateTime $contractDate
    ) {
    }

    public function jsonSerialize(): mixed
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'code' => $this->code,
            'contractDate' => $this->contractDate->format('Y-m-d')
        ];
    }

    public function getCustomerId(): int
    {
        return $this->id;
    }

    public function setCustomerId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getContractDate(): DateTime
    {
        return $this->contractDate;
    }

    public function setContractDate(DateTime $contractDate): void
    {
        $this->contractDate = $contractDate;
    }
}
