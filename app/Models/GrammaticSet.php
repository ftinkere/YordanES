<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class GrammaticSet extends Model
{
    use HasUuids;
    protected $primaryKey = 'uuid';

    protected $table = 'grammatic_set';

    protected $fillable = [
        'uuid',
        'parent_type',
        'parent_id',
        'group',
        'value_id',
        'order',
    ];

    public function parent(): MorphTo
    {
        return $this->morphTo();
    }

    public function value(): BelongsTo
    {
        return $this->belongsTo(GrammaticValue::class, 'value_id');
    }

    protected function casts(): array
    {
        return [
            'uuid' => 'string',
            'parent_id' => 'string',
        ];
    }
}
