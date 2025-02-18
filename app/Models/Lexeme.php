<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\EventSourcing\Projections\Projection;

class Lexeme extends Projection
{
    protected $fillable = [
        'uuid',
        'language_uuid',
        'vocabula_uuid',
        'group',
        'order',
        'suborder',
        'short',
        'full',
    ];

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_uuid');
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(DictionaryArticle::class, 'article_uuid');
    }

    public function blocks(): HasMany
    {
        return $this->hasMany(LexemeBlock::class, 'lexeme_uuid');
    }

    protected function casts(): array
    {
        return [
            'uuid' => 'string',
        ];
    }
}
