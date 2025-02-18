<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\EventSourcing\Projections\Projection;

class LexemeBlock extends Projection
{
    protected $fillable = [
        'uuid',
        'lexeme_id',
        'name',
        'content',
    ];

    public function lexeme(): BelongsTo
    {
        return $this->belongsTo(Lexeme::class);
    }

    protected function casts(): array
    {
        return [
            'uuid' => 'string',
        ];
    }
}
