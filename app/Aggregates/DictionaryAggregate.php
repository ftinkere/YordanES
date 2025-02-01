<?php

namespace App\Aggregates;

use Ramsey\Uuid\UuidFactoryInterface;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class DictionaryAggregate extends AggregateRoot
{
    public function __construct(
        protected UuidFactoryInterface $uuidFactory,
    ) {}

    public function article(string $uuid): ArticleAggregate
    {
        return ArticleAggregate::retrieve($uuid);
    }
}
