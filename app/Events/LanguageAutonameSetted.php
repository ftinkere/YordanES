<?php

namespace App\Events;

class LanguageAutonameSetted extends \Spatie\EventSourcing\StoredEvents\ShouldBeStored
{
    public function __construct(
        public string $uuid,
        public string $autoname,
        public string $autoname_transcription,
    ) {}
}
