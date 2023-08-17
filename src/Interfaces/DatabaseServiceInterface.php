<?php

namespace App\Interfaces;

use App\Collections\CustomerCollection;
use App\Entity\Customer;

interface DatabaseServiceInterface
{
    /**
     * Selects customer(s) from the database
     * @throws Exception
     */
    public function select(?int $customerId): null|Customer|CustomerCollection;

    /** Inserts customer(s) into the database */
    public function insert(CustomerCollection $data): bool;

    /** Updates customer(s) */
    public function update(CustomerCollection $data): bool;

    /** Deletes customer(s) */
    public function delete(CustomerCollection $data): bool;
}
