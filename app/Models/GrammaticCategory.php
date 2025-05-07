<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GrammaticCategory extends Model
{
    use HasUuids;
    protected $primaryKey = 'uuid';

    protected $fillable = [
        'uuid',
        'name',
        'code',
        'description',
        'language_id',
        'pos_id',
        'parent_uuid',
        'is_multiple',
        'order',
    ];

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function partOfSpeech(): BelongsTo
    {
        return $this->belongsTo(GrammaticPartOfSpeech::class, 'pos_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_uuid');
    }

    public function values(): HasMany
    {
        return $this->hasMany(GrammaticValue::class, 'category_id')
            ->orderBy('order');
    }

    protected function casts(): array
    {
        return [
            'uuid' => 'string',
            'parent_uuid' => 'string',
            'is_multiple' => 'boolean',
        ];
    }
}
