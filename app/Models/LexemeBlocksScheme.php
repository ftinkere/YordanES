<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\EventSourcing\Projections\Projection;

class LexemeBlocksScheme extends Projection
{
    protected $table = 'lexeme_blocks_scheme';

    protected $fillable = [
        'uuid',
        'language_id',
        'name',
    ];

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    protected function casts(): array
    {
        return [
            'uuid' => 'string',
        ];
    }
}
