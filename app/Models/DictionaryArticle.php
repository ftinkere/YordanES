<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Mews\Purifier\Casts\CleanHtml;

class DictionaryArticle extends Model
{
    use HasUuids;

    protected $primaryKey = 'uuid';

    protected $fillable = [
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
            'article' => CleanHtml::class,
            'is_published' => 'boolean',
        ];
    }

    public function lexemes()
    {
        return $this->hasMany(Lexeme::class, 'article_uuid')
            ->with('tags')
            ->orderBy('group')
            ->orderBy('order')
            ->orderBy('suborder')
            ;
    }

    public function lexemesGrouped()
    {
        return $this->lexemes()
            ->get()
            ->groupBy('group');
    }

    public function files()
    {
        return $this->hasMany(File::class, 'parent_id')
            ->where('parent_type', self::class);
    }
}
