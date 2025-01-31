<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vocabula extends Model
{
    protected $table = 'vocables';

    protected $fillable = [
        'uuid',
        'article_uuid',
        'vocabula',
        'adaptation',
        'transcription',
        'image',
    ];

    public function article()
    {
        return $this->belongsTo(DictionaryArticle::class, 'article_uuid');
    }

    protected function casts(): array
    {
        return [
            'uuid' => 'string',
        ];
    }
}
