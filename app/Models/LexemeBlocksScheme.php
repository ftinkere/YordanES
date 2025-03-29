<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LexemeBlocksScheme extends Model
{
    use HasUuids;

    protected $primaryKey = 'uuid';

    protected $table = 'lexeme_blocks_scheme';

    protected $fillable = [
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
