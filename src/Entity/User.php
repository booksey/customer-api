<?php

namespace App\Entity;


use DateTime;
use JMS\Serializer\Annotation\Type as SerializerType;

class User
{
    private int $id;
    private string $address;
    private ?string $code;
    #[SerializerType("DateTime<'Y-m-d'>")]
    private DateTime $contractDate;

    public function getId(): int
    {
        return $this->id;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function getCode(): string
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
