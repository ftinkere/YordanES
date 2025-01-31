<?php

declare(strict_types=1);

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
    public function onLanguageCreated(LanguageCreated $languageCreated): void
    {
        $language = new Language();
        $language->uuid = $languageCreated->uuid;
        $language->name = $languageCreated->name;
        $language->creator_uuid = $languageCreated->creator_uuid;
        $language->created_at = $languageCreated->createdAt();
        $language->writeable()->save();
    }

    public function onLanguageNameSetted(LanguageNameSetted $languageNameSetted): void
    {
        $language = Language::findOrFail($languageNameSetted->uuid);
        $language->name = $languageNameSetted->name;
        $language->updated_at = $languageNameSetted->createdAt();
        $language->writeable()->save();
    }

    public function onLanguageAutonameSetted(LanguageAutonameSetted $languageAutonameSetted): void
    {
        $language = Language::findOrFail($languageAutonameSetted->uuid);
        $language->autoname = $languageAutonameSetted->autoname;
        $language->autoname_transcription = $languageAutonameSetted->autoname_transcription;
        $language->updated_at = $languageAutonameSetted->createdAt();
        $language->writeable()->save();
    }

    public function onLanguageDescriptionSetted(LanguageDescriptionSetted $languageDescriptionSetted): void
    {
        $language = Language::findOrFail($languageDescriptionSetted->uuid);
        $description = Description::where([
            'language_uuid' => $languageDescriptionSetted->uuid,
            'title' => $languageDescriptionSetted->title,
        ])->firstOrCreate();

        $description->language_uuid = $languageDescriptionSetted->uuid;
        $description->title = $languageDescriptionSetted->title;
        $description->description = $languageDescriptionSetted->description;
        if ($description->wasRecentlyCreated) {
            $description->created_at = $languageDescriptionSetted->createdAt();
        }

        $description->updated_at = $languageDescriptionSetted->createdAt();
        $description->save();

        $language->updated_at = $languageDescriptionSetted->createdAt();
    }
}
