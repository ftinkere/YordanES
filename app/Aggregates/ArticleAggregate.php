<?php

namespace App\Aggregates;

use Ramsey\Uuid\UuidFactoryInterface;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class ArticleAggregate extends AggregateRoot
{
    public string $uuid;

    public function __construct(
        protected UuidFactoryInterface $uuidFactory,
    ) {}
}
