<?php

namespace App\Observers;

use App\Models\BaseProjection;
use Spatie\EventSourcing\Projections\Exceptions\ReadonlyProjection;

class ProjectionObserver
{
    public function updating(BaseProjection $projection): void
    {
        if ($projection->isWriteable()) {
            return;
        }

        $this->preventChanges($projection);
    }

    public function creating(BaseProjection $projection): void
    {
        if ($projection->isWriteable()) {
            return;
        }

        $this->preventChanges($projection);
    }

    public function saving(BaseProjection $projection): void
    {
        if ($projection->isWriteable()) {
            return;
        }

        $this->preventChanges($projection);
    }

    public function deleting(BaseProjection $projection): void
    {
        if ($projection->isWriteable()) {
            return;
        }

        $this->preventChanges($projection);
    }

    private function preventChanges(BaseProjection $projection): void
    {
        throw ReadonlyProjection::new(get_class($projection));
    }
}
