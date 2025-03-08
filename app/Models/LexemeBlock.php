<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LexemeBlock extends Model
{
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;

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
