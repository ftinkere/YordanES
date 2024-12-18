<?php

namespace App\Models;

use Spatie\EventSourcing\StoredEvents\Models\EloquentStoredEvent;

class StoredEvent extends EloquentStoredEvent
{
    protected $connection = 'mysql';

    protected static function boot()
    {
        parent::boot();

        static::updating(function (self $model) {
            if ($model->isDirty('meta_data') || $model->wasChanged('meta_data')) {
                $model->aggregate_uuid = $model->meta_data['aggregate-root-uuid'] ?? null;
            }
        });
    }
}
