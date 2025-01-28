<?php

namespace App\Events\Language;

class LanguageNameSetted extends \Spatie\EventSourcing\StoredEvents\ShouldBeStored
{
    public function __construct(
        public string $uuid,
        public string $name,
    ) {}
}