<?php

namespace App\Aggregates;

use App\Events\Articles\ArticleCreated;
use App\Events\Articles\ArticleLexemeAdded;
use Ramsey\Uuid\UuidFactoryInterface;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class ArticleAggregate extends AggregateRoot
{
    public string $uuid;
    public string $language_uuid;
//    public string $vacabula;
//    public string $transcription;
//    public string $short;
//    public string $full;

    public function __construct(
        protected UuidFactoryInterface $uuidFactory,
    ) {}

    public function create(string $language_uuid, string $vocabula, string $transcription, $adaptation, string $short, string $full): static
    {
        $uuid = $this->uuidFactory->uuid7();
        if (! is_string($uuid)) {
            $uuid = (string)$uuid;
        }

        $this->loadUuid($uuid);

        $this->recordThat(new ArticleCreated($uuid, $language_uuid, $vocabula, $transcription, $adaptation, $short, $full));

        return $this;
    }

    public function applyArticleCreated(ArticleCreated $articleCreated)
    {
        $this->uuid = $articleCreated->uuid;
        $this->language_uuid = $articleCreated->language_uuid;
        //
    }

    public function addLexeme($short, $full, $group, $order, $suborder): static
    {
        $this->recordThat(new ArticleLexemeAdded($this->uuid, $short, $full, $group, $order, $suborder));

        return $this;
    }

    public function applyArticleLexemeAdded(ArticleLexemeAdded $articleLexemeAdded)
    {
        //
    }

    public function addLexemesOrdered(array $lexemes): static
    {
        foreach ($lexemes as $order => $lexemesSub) {
            foreach ($lexemesSub as $suborder => $lexeme) {
                $this->addLexeme($lexeme['short'], $lexeme['full'], $lexeme['group'], $order, $suborder);
            }
        }

        return $this;
    }
}
