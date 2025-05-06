<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GrammaticPartOfSpeech extends Model
{
    use HasUuids;
    protected $primaryKey = 'uuid';

    protected $fillable = [
        'uuid',
        'name',
        'code',
        'description',
        'language_id',
        'order',
    ];

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public static function defaults()
    {
        return self::where('language_id', null)
            ->orderBy('order');
    }

    protected function casts(): array
    {
        return [
            'uuid' => 'string',
        ];
    }
}
