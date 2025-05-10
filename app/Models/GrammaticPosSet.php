<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GrammaticPosSet extends Model
{
    use HasUuids;

    protected $primaryKey = 'uuid';
    protected $table = 'grammatic_pos_set';

    protected $fillable = [
        'uuid',
        'parent_type',
        'parent_id',
        'group',
        'pos_id',
    ];

    public function pos(): BelongsTo
    {
        return $this->belongsTo(GrammaticPartOfSpeech::class, 'pos_id', 'uuid');
    }

    protected function casts(): array
    {
        return [
            'uuid' => 'string',
            'parent_id' => 'string',
        ];
    }
}
