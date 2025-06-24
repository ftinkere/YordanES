<?php

namespace App\Domain\Models;

use App\Domain\Vos\Translatable;
use Illuminate\Support\Carbon;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Language {
    public UuidInterface $id;
    public Translatable $name;
    public Author $author;
    public bool $isPublished;
    public Carbon $createdAt;


    /** @var array<Article> $articles */
    public array $articles;


    public function __construct() {
        $this->id = Uuid::uuid7();
        $this->name = new Translatable();
        $this->author = new Author();
        $this->isPublished = false;
        $this->createdAt = Carbon::now();
    }

    public static function load(string $uuid): self {
        $model = \App\Models\Language::with('author')->find($uuid);

        $return = new self;
        $return->id = Uuid::fromString($model->uuid);
        $return->name->content = $model->name;
        $return->name->translation = $model->autoname;
        $return->name->transcription = $model->autoname_transcription;
        $return->author->id = $model->creator_uuid;
        $return->author->name = $model->author->name;
        $return->isPublished = $model->is_published;
        $return->createdAt = $model->created_at;

        return $return;
    }

    public function withArticles(): self {
        $articles = [];
        $model = \App\Models\Language::with('dictionary')->find($this->id);
        foreach ($model->dictionary as $item) {
            $articles[] = Article::load($item);
        }
        $this->articles = $articles;
        return $this;
    }
}