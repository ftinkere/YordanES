<?php

namespace App\Models;

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
}
