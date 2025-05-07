<?php

namespace App\Livewire\Languages\GrammaticConstructor;

use App\Models\GrammaticCategory;
use App\Models\GrammaticPartOfSpeech;
use App\Models\Language;
use Flux\Flux;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Renderless;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CategoriesTab extends Component
{
    #[Locked]
    public Language $language;

    public string $posUuid;

    #[Validate('required|min:3')]
    public string $categoryName;
    #[Validate('required|min:1')]
    public string $categoryCode;
    public string $categoryDescription;

    #[Locked]
    public ?GrammaticPartOfSpeech $partOfSpeech {
        get {
            if (! isset($this->posUuid) || ! isset($this->language)) {
                return null;
            }
            $pos = $this->posUuid ? GrammaticPartOfSpeech::find($this->posUuid) : null;

            if ($pos?->language_id != $this->language->uuid) {
                return null;
            }

            return $pos;
        }

        set(\App\Models\GrammaticPartOfSpeech|null $value) { }
    }

    public function categoryAdd()
    {
        $this->validate();
        if (! isset($this->posUuid) || ! $this->posUuid) {
            $this->addError('posUuid', 'Выберите часть речи.');
            return;
        }

        $pos = $this->partOfSpeech;
        if ($pos == null) {
            return;
        }

        $category = new GrammaticCategory;
        $category->name = mb_trim($this->categoryName);
        $category->code = mb_trim($this->categoryCode);
        $category->description = $this->categoryDescription ?? null;
        $category->language_id = $this->language->uuid;
        $category->pos_id = $pos->uuid;
        $category->order = GrammaticCategory::where('pos_id', $pos->uuid)->max('order') + 1;
        $category->save();

        $this->categoryName = '';
        $this->categoryCode = '';
        $this->categoryDescription = '';
        Flux::modal('add-category')->close();
    }

    public function deleteCategory($uuid)
    {
        $category = GrammaticCategory::find($uuid);
        if ($category->language_id !== $this->language->uuid) {
            return;
        }

        $category->delete();
    }

    #[Renderless]
    public function reorderCategories($uuid, $oldIndex, $newIndex)
    {
        $category = GrammaticCategory::findOrFail($uuid);
        if ($category->language_id !== $this->language->uuid) {
            return;
        }
        $categoryOther = GrammaticCategory::where('language_id', $this->language->uuid)
            ->where('pos_id', $category->pos_id)
            ->where('order', $newIndex);
        if ($categoryOther->exists()) {
            $categoryOther->update(['order' => $oldIndex]);
        }
        $category->update(['order' => $newIndex]);
    }

    public function render()
    {
        return view('livewire.languages.grammatic-constructor.categories-tab')
            ->layout('components.layouts.language', ['language' => $this->language]);
    }
}
