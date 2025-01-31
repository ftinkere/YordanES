<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lexeme extends Model
{
    protected $fillable = [
        'uuid',
        'language_uuid',
        'vocabula_uuid',
        'group',
        'order',
        'suborder',
        'short',
    ];

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_uuid');
    }

    public function vocabula(): BelongsTo
    {
        return $this->belongsTo(Vocabula::class, 'vocabula_uuid');
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
