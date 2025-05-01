<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Tag extends Model
{
    use HasUuids;

    protected $primaryKey = 'uuid';

    const array colors = ['red', 'orange', 'amber', 'yellow', 'lime', 'green', 'emerald', 'teal', 'cyan', 'sky', 'blue', 'indigo', 'violet', 'purple', 'fuchsia', 'pink', 'rose'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (! in_array($model->color, self::colors)) {
                $colorSeed = $model->name;
                $hash = crc32((string) $colorSeed);
                $model->color = self::colors[$hash % count(self::colors)];
            }
        });
    }

    protected $fillable = [
        'uuid',
        'name',
        'color',
        'taggable_type',
        'taggable_id',
    ];

    protected function casts(): array
    {
        return [
            'uuid' => 'string',
            'taggable_id' => 'string',
        ];
    }

    public function taggable(): MorphTo
    {
        return $this->morphTo();
    }
}
