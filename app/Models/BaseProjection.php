<?php

namespace App\Models;

use App\Observers\ProjectionObserver;
use MongoDB\Laravel\Eloquent\Model;

/**
 * @method static static create(array $parameters = [])
 * @method static static|null find(string $uuid)
 */
abstract class BaseProjection extends Model
{
    private bool $isWriteable = false {
        get {
            return $this->isWriteable;
        }
    }

    protected static function boot(): void
    {
        parent::boot();

        static::observe(ProjectionObserver::class);
    }

    public static function new(): static
    {
        return new static;
    }

    public function isWriteable(): bool
    {
        return $this->isWriteable;
    }

    public function getKeyName()
    {
        return '_id';
    }

    public function getKeyType()
    {
        return 'string';
    }

    public function getIncrementing()
    {
        return false;
    }

    public function getRouteKeyName()
    {
        return '_id';
    }

    public function writeable(): self
    {
        $clone = clone $this;

        $clone->isWriteable = true;

        return $clone;
    }

    public function refresh()
    {
        $this->isWriteable = false;

        return parent::refresh();
    }

    public function fresh($with = [])
    {
        $this->isWriteable = false;

        return parent::fresh($with);
    }

    public function newInstance($attributes = [], $exists = false)
    {
        $instance = parent::newInstance($attributes, $exists);

        $instance->isWriteable = $this->isWriteable;

        return $instance;
    }
}
