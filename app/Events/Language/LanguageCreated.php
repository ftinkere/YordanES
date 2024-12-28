<?php

namespace App\Events\Language;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class LanguageCreated extends ShouldBeStored
{
    public function __construct(
        public string $uuid,
        public string $name,
        public string $creator_uuid,
    ) {}
}