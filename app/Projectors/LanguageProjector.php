<?php

namespace App\Projectors;

use App\Events\Language\LanguageCreated;
use App\Events\Language\LanguageDescriptionSetted;
use App\Events\Language\LanguageNameSetted;
use App\Events\LanguageAutonameSetted;
use App\Models\Description;
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
        $language->created_at = $event->createdAt();
        $language->writeable()->save();
    }

    public function onLanguageNameSetted(LanguageNameSetted $event)
    {
        $language = Language::findOrFail($event->uuid);
        $language->name = $event->name;
        $language->updated_at = $event->createdAt();
        $language->writeable()->save();
    }

    public function onLanguageAutonameSetted(LanguageAutonameSetted $event)
    {
        $language = Language::findOrFail($event->uuid);
        $language->autoname = $event->autoname;
        $language->autoname_transcription = $event->autoname_transcription;
        $language->updated_at = $event->createdAt();
        $language->writeable()->save();
    }

    public function onLanguageDescriptionSetted(LanguageDescriptionSetted $event)
    {
        $language = Language::findOrFail($event->uuid);
        $description = Description::where([
            'language_uuid' => $event->uuid,
            'title' => $event->title,
        ])->firstOrCreate();

        $description->language_uuid = $event->uuid;
        $description->title = $event->title;
        $description->description = $event->description;
        if ($description->wasRecentlyCreated) {
            $description->created_at = $event->createdAt();
        }
        $description->updated_at = $event->createdAt();
        $description->save();

        $language->updated_at = $event->createdAt();
    }
}
