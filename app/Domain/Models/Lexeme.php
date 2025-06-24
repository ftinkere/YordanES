<?php

namespace App\Domain\Models;

use App\Domain\Vos\RichText;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Lexeme {
    public UuidInterface $id;
    public RichText $article;

    public function __construct() {
        $this->id = Uuid::uuid7();
    }
}