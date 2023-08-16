<?php

namespace App\Entity;

use DateTime;
use JMS\Serializer\Annotation\Type as SerializerType;

class User
{
    private int $customerId;
    private string $address;
    private ?string $code;
    #[SerializerType("DateTime<'Y-m-d'>")]
    private DateTime $contractDate;

    public function getCustomerId(): int
    {
        return $this->customerId;
    }

    public function setCustomerId(int $customerId): void
    {
        $this->customerId = $customerId;
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
