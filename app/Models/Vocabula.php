<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vocabula extends Model
{
    protected $table = 'vocables';

    protected $fillable = [
        'uuid',
        'article_uuid',
        'vocabula',
        'adaptation',
        'transcription',
        'image',
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(DictionaryArticle::class, 'article_uuid');
    }

    protected function casts(): array
    {
        return [
            'uuid' => 'string',
        ];
    }
}
