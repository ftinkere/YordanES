<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Mews\Purifier\Casts\CleanHtml;

class Lexeme extends Model
{
    use HasUuids;

    protected $primaryKey = 'uuid';

    protected $fillable = [
        'language_uuid',
        'article_uuid',
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

    public function tags(): MorphMany
    {
        return $this->morphMany(Tag::class, 'taggable');
    }

    public function partOfSpeech(): HasOneThrough
    {
        return $this->hasOneThrough(
            GrammaticPartOfSpeech::class,
            GrammaticPosSet::class,
            'parent_id',
            'uuid',
            'uuid',
            'pos_id'
        )->where('grammatic_pos_set.parent_type', self::class);
    }

    public function posSet()
    {
        return $this->hasOne(GrammaticPosSet::class, 'parent_id', 'uuid')
            ->where('parent_type', self::class);
    }

    public function gramSet()
    {
        return $this->morphMany(GrammaticSet::class, 'parent');
    }

    public function firstLineFull(): false|string
    {
        return strtok($this->full, "\n");
    }

    protected function casts(): array
    {
        return [
            'uuid' => 'string',
            'short' => CleanHtml::class,
            'full' => CleanHtml::class,
        ];
    }
}
