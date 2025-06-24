<?php

namespace App\Domain\Models;

use App\Domain\Vos\Name;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Author {
    public UuidInterface $id;
    public Name $name;

    public function __construct() {
        $this->id = Uuid::uuid7();
    }
}