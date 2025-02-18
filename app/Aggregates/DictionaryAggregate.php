<?php

namespace App\Aggregates;

use Ramsey\Uuid\UuidFactoryInterface;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class DictionaryAggregate extends AggregateRoot
{
    public string $language_uuid;

    public function __construct(
        protected UuidFactoryInterface $uuidFactory,
    ) {
        $this->language_uuid = $this->uuid();
    }

    public function createArticle($vocabula, $transcription, $adaptation, $short, $full): ArticleAggregate
    {
        return app(ArticleAggregate::class)
            ->create($this->language_uuid, $vocabula, $transcription, $adaptation, $short, $full);
    }

    public function createArticleFull($vocabula, $transcription, $adaptation, $short, $full, $lexemes): ArticleAggregate
    {
        return $this->createArticle($vocabula, $transcription, $adaptation, $short, $full)
            ->addLexemesOrdered($lexemes);
    }

    public function article(string $uuid): ArticleAggregate
    {
        return ArticleAggregate::retrieve($uuid);
    }
}
