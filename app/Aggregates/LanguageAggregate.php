<?php

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

    public $uuidGenerate;


    public function __construct()
    {
        $this->uuidGenerate = [Uuid::class, 'uuid7'];
    }

    public function withGenerators($uuidGenerator)
    {
        $this->uuidGenerate = $uuidGenerator;
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

    public function applyLanguageCreated(LanguageCreated $event): void
    {
        $this->uuid = $event->uuid;
        $this->name = $event->name;
    }

    public function setName(string $name): self
    {
        $this->recordThat(new LanguageNameSetted($this->uuid, $name));

        return $this;
    }

    public function applyLanguageNameSetted(LanguageNameSetted $event): void
    {
        $this->name = $event->name;
    }

    public function setAutoname(string $autoname, string $autoname_transcription): self
    {
        $this->recordThat(new LanguageAutonameSetted($this->uuid, $autoname, $autoname_transcription));

        return $this;
    }

    public function applyLanguageAutonameSetted(LanguageAutonameSetted $event): void
    {
        $this->autoname = $event->autoname;
        $this->autoname_transcription = $event->autoname_transcription;
    }

    public function setDescription(string $title, string $description): self
    {
        $this->recordThat(new LanguageDescriptionSetted($this->uuid, $title, $description));

        return $this;
    }

    public function applyLanguageDescriptionSetted(LanguageDescriptionSetted $event): void
    {
        $this->descriptions[$event->title] = $event->description;
    }
}
