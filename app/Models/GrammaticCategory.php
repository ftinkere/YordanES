<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kalnoy\Nestedset\NodeTrait;

class GrammaticCategory extends Model
{
    use NodeTrait;
    use HasUuids;

    protected $primaryKey = 'uuid';

    protected $fillable = [
        'language_id',
        'name',
        'description',
    ];

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function values(): HasMany
    {
        return $this->hasMany(GrammaticValue::class);
    }

    protected function casts(): array
    {
        return [
            'uuid' => 'string',
        ];
    }

    protected function getScopeAttributes(): array
    {
        return [ 'language_uuid' ];
    }

    public static function defaultCategories(): Collection
    {
        return self::scoped(['language_uuid' => null])->withDepth()->get();
    }
}
