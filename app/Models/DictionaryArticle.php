<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DictionaryArticle extends Model
{
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'uuid',
        'language_uuid',
        'article',
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

    public function lexemesGrouped()
    {
        return $this->hasMany(Lexeme::class, 'article_uuid')
            ->get()
            ->groupBy('group');
    }
}
