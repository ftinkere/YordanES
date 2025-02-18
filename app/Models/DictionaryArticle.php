<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\EventSourcing\Projections\Projection;

class DictionaryArticle extends Projection
{
    protected $fillable = [
        'uuid',
        'language_uuid',
        'short',
        'full',
        'vocabula',
        'adaptation',
        'transcription',
        'is_published',
    ];

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_uuid');
    }

    protected function casts(): array
    {
        return [
            'uuid' => 'string',
            'is_published' => 'boolean',
        ];
    }

    public function lexemes()
    {
        return$this->hasMany(Lexeme::class, 'article_uuid');
    }
}
