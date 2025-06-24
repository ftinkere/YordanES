<?php

namespace App\Domain\Models;

use App\Domain\Vos\GramSet;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class LexemeGroup {
    public UuidInterface $id;
    /** @var array<Lexeme|LexemeGroup> $lexemes */
    public array $lexemes;
    public GramSet $gramset;

    public function __construct() {
        $this->id = Uuid::uuid7();
    }
}