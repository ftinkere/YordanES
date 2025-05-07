<?php

namespace App\Livewire\Languages\GrammaticConstructor;

use App\Models\GrammaticCategory;
use App\Models\GrammaticValue;
use App\Models\Language;
use Flux\Flux;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Renderless;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ValuesTab extends Component
{
    #[Locked]
    public Language $language;

    #[Validate('required|min:2')]
    public string $valueName;
    #[Validate('required|min:1')]
    public string $valueCode;
    public string $valueDescription;

    public string $categoryUuid;
    #[Locked]
    public ?GrammaticCategory $category {
        get {
            if (! isset($this->categoryUuid) || ! isset($this->language)) {
                return null;
            }
            $cat = $this->categoryUuid ? GrammaticCategory::find($this->categoryUuid) : null;

            if ($cat?->language_id != $this->language->uuid) {
                return null;
            }

            return $cat;
        }

        set(GrammaticCategory|null $value) { }
    }

    public function addValue() {
        $this->validate();
        if (! isset($this->categoryUuid) || ! $this->categoryUuid) {
            $this->addError('categoryUuid', 'Выберите категорию.');
            return;
        }
        $category = $this->category;
        if ($category == null) {
            return;
        }
        $value = new GrammaticValue;
        $value->name = mb_trim($this->valueName);
        $value->code = mb_trim($this->valueCode);
        $value->description = $this->valueDescription ?? null;
        $value->language_id = $this->language->uuid;
        $value->category_id = $category->uuid;
        $value->order = GrammaticValue::where('category_id', $category->uuid)->max('order') + 1;
        $value->save();

        $this->valueName = '';
        $this->valueCode = '';
        $this->valueDescription = '';
        Flux::modal('add-value')->close();
    }

    public function deleteValue($uuid) {
        $value = GrammaticValue::find($uuid);
        if ($value->language_id !== $this->language->uuid) {
            return;
        }
        $value->delete();
    }

    #[Renderless]
    public function reorderValues($uuid, $oldIndex, $newIndex)
    {
        $value = GrammaticValue::findOrFail($uuid);
        if ($value->language_id !== $this->language->uuid) {
            return;
        }
        $valueOther = GrammaticValue::where('language_id', $this->language->uuid)
            ->where('category_id', $value->category_id)
            ->where('order', $newIndex);
        if ($valueOther->exists()) {
            $valueOther->update(['order' => $oldIndex]);
        }
        $value->update(['order' => $newIndex]);
    }

    public function render()
    {
        return view('livewire.languages.grammatic-constructor.values-tab')
            ->layout('components.layouts.language', ['language' => $this->language]);
    }
}
