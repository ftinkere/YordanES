<?php

namespace App\Domain\Vos;

class Translatable {
    public string $content;
    public ?string $translation;
    public ?string $transcription;
}