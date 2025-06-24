<?php

namespace App\Domain\Models;

use App\Domain\Vos\Vocabula;
use App\Models\DictionaryArticle;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Article {
    public UuidInterface $id;
    public Vocabula $vocabula;
    public bool $isPublished;
    public Carbon $createdAt;
    public Carbon $updatedAt;

    /** @var array<LexemeGroup> $lexemeGroups */
    public array $lexemeGroups;

    public function __construct() {
        $this->id = Uuid::uuid7();
        $this->vocabula = new Vocabula();
        $this->lexemeGroups = [];
        $this->isPublished = false;
        $this->createdAt = Carbon::now();
        $this->updatedAt = Carbon::now();
    }

    public static function load(DictionaryArticle $model): self {
        $article = new self();
        $article->id = Uuid::fromString($model->uuid);
        $article->vocabula->value = $model->vocabula;
        $article->vocabula->transcription = $model->transcription;
        $article->vocabula->adaptation = $model->adaptation;
        $article->isPublished = $model->is_published;
        $article->createdAt = $model->created_at;
        $article->updatedAt = $model->updated_at;
        $article->lexemeGroups = []; // TODO

        return $article;
    }
}