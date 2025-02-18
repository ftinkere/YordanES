<?php

namespace App\Events\Articles;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class ArticleLexemeAdded extends ShouldBeStored
{
    public function __construct(
        public string $uuid,
        public string $short,
        public string $full,
        public int $group,
        public int $order,
        public int $suborder,
    ) {}
}