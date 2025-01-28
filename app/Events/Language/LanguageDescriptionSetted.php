<?php

namespace App\Events\Language;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class LanguageDescriptionSetted extends ShouldBeStored {
    public function __construct(
        public string $uuid,
        public string $title,
        public string $description,
    ) {}
}