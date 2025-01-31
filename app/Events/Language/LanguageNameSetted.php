<?php

declare(strict_types=1);

namespace App\Events\Language;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class LanguageNameSetted extends ShouldBeStored
{
    public function __construct(
        public string $uuid,
        public string $name,
    ) {}
}
