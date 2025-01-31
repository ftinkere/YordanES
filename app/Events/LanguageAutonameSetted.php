<?php

declare(strict_types=1);

namespace App\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class LanguageAutonameSetted extends ShouldBeStored
{
    public function __construct(
        public string $uuid,
        public string $autoname,
        public string $autoname_transcription,
    ) {}
}
