<?php

declare(strict_types=1);

namespace App\Aggregates;

use App\Events\Language\LanguageCreated;
use App\Events\Language\LanguageDescriptionSetted;
use App\Events\Language\LanguageNameSetted;
use App\Events\LanguageAutonameSetted;
use Ramsey\Uuid\Uuid;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class LanguageAggregate extends AggregateRoot
{
    public string $uuid;

    public string $name;

    public string $autoname;

    public string $autoname_transcription;

    /** @var string[] $descriptions */
    public array $descriptions;

    public mixed $uuidGenerate = [Uuid::class, 'uuid7'];


    public function __construct()
    {
    }

    public function withGenerators(mixed $uuidGenerator): self
    {
        $this->uuidGenerate = $uuidGenerator;

        return $this;
    }

    public function create(string $name, string $user_uuid): self
    {
        $uuid = call_user_func($this->uuidGenerate);
        if (! is_string($uuid)) {
            $uuid = (string)$uuid;
        }

        $this->loadUuid($uuid);

        $this->recordThat(new LanguageCreated($uuid, $name, $user_uuid));

        return $this;
    }

    public function applyLanguageCreated(LanguageCreated $languageCreated): void
    {
        $this->uuid = $languageCreated->uuid;
        $this->name = $languageCreated->name;
    }

    public function setName(string $name): self
    {
        $this->recordThat(new LanguageNameSetted($this->uuid, $name));

        return $this;
    }

    public function applyLanguageNameSetted(LanguageNameSetted $languageNameSetted): void
    {
        $this->name = $languageNameSetted->name;
    }

    public function setAutoname(string $autoname, string $autoname_transcription): self
    {
        $this->recordThat(new LanguageAutonameSetted($this->uuid, $autoname, $autoname_transcription));

        return $this;
    }

    public function applyLanguageAutonameSetted(LanguageAutonameSetted $languageAutonameSetted): void
    {
        $this->autoname = $languageAutonameSetted->autoname;
        $this->autoname_transcription = $languageAutonameSetted->autoname_transcription;
    }

    public function setDescription(string $title, string $description): self
    {
        $this->recordThat(new LanguageDescriptionSetted($this->uuid, $title, $description));

        return $this;
    }

    public function applyLanguageDescriptionSetted(LanguageDescriptionSetted $languageDescriptionSetted): void
    {
        $this->descriptions[$languageDescriptionSetted->title] = $languageDescriptionSetted->description;
    }
}
