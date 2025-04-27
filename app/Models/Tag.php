<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Tag extends Model
{
    public $colors = ['red', 'orange', 'amber', 'yellow', 'lime', 'green', 'emerald', 'teal', 'cyan', 'sky', 'blue', 'indigo', 'violet', 'purple', 'fuchsia', 'pink', 'rose'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (! in_array($model->color, $this->colors)) {
                $colorSeed = $model->name;
                $hash = crc32((string) $colorSeed);
                $model->color = $this->colors[$hash % count($this->colors)];
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
