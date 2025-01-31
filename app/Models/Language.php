<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\EventSourcing\Projections\Projection;

class Language extends Projection
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'autoname',
        'autoname_transcription',
        'flag',
    ];

    protected function casts(): array
    {
        return [
            'uuid' => 'string',
        ];
    }

    public function isAuthor(?User $user): bool
    {
        return $this->creator_uuid === $user?->uuid;
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_uuid', 'uuid');
    }

    public function grammaticCategories(): HasMany
    {
        return $this->hasMany(GrammaticCategory::class, 'language_uuid', 'uuid');
    }

    public function lexemeBlocksScheme(): HasMany
    {
        return $this->hasMany(LexemeBlocksScheme::class, 'language_uuid', 'uuid');
    }

    public function description(string $title): string
    {
        return $this->hasOne(Description::class, 'language_uuid', 'uuid')
            ->where('title', $title)
            ->first()
            ?->description ?? '';
    }
}
