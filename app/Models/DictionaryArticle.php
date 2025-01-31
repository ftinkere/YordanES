<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DictionaryArticle extends Model
{
    protected $table = 'dictionary_article';

    protected $fillable = [
        'uuid',
        'language_uuid',
    ];

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_uuid');
    }

    public function vocables(): HasMany
    {
        return $this->hasMany(Vocabula::class, 'article_uuid', 'uuid');
    }

    protected function casts(): array
    {
        return [
            'uuid' => 'string',
        ];
    }
}
