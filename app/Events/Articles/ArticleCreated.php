<?php

namespace App\Events\Articles;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class ArticleCreated extends ShouldBeStored
{
    public function __construct(
        public string $uuid,
        public string $language_uuid,
        public string $vocabula,
        public string $transcription,
        public string $adaptation,
        public string $short,
        public string $full
    ) {}
}