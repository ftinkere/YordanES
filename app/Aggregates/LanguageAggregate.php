<?php

namespace App\Aggregates;

use App\Events\Language\LanguageCreated;
use Ramsey\Uuid\Uuid;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class LanguageAggregate extends AggregateRoot
{
    public string $uuid;
    public string $name;

    public $uuidGenerate;


    public function __construct()
    {
        $this->uuidGenerate = [Uuid::class, 'uuid7'];
    }

    public function withGenerators($uuidGenerator)
    {
        $this->uuidGenerate = $uuidGenerator;
    }

    public function create(string $name, string $user_uuid)
    {
        $uuid = call_user_func($this->uuidGenerate);
        if (! is_string($uuid)) {
            $uuid = (string)$uuid;
        }
        $this->loadUuid($uuid);

        $this->recordThat(new LanguageCreated($uuid, $name, $user_uuid));

        return $this;
    }

    public function applyLanguageCreated(LanguageCreated $event)
    {
        $this->uuid = $event->uuid;
        $this->name = $event->name;
    }
}
