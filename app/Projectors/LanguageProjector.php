<?php

namespace App\Projectors;

use App\Events\Language\LanguageCreated;
use App\Models\Language;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class LanguageProjector extends Projector
{
    public function onLanguageCreated(LanguageCreated $event)
    {
        $language = new Language();
        $language->uuid = $event->uuid;
        $language->name = $event->name;
        $language->creator_uuid = $event->creator_uuid;
        $language->writeable()->save();
    }
}
