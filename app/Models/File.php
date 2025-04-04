<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasUuids;
    protected $primaryKey = 'uuid';

    protected $fillable = [
        'uuid',
        'parent_type',
        'parent_id',
        'path',
        'width',
        'height',
    ];

    protected function casts(): array
    {
        return [
            'uuid' => 'string',
            'parent_id' => 'string',
        ];
    }
}
