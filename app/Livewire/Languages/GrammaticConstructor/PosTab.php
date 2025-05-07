<?php

namespace App\Livewire\Languages\GrammaticConstructor;

use App\Models\GrammaticPartOfSpeech;
use App\Models\Language;
use Flux\Flux;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Renderless;
use Livewire\Attributes\Validate;
use Livewire\Component;

class PosTab extends Component
{
    #[Locked]
    public Language $language;

    #[Validate('required|min:3')]
    public string $posName;
    #[Validate('required|min:1')]
    public string $posCode;

    public string $posDescription;


    public function addPartOfSpeech() {
        $this->validate();

        if (GrammaticPartOfSpeech::where('language_id', $this->language->uuid)
            ->where('name', mb_trim($this->posName))
            ->exists()
        ) {
            $this->addError('posName', 'Такая часть речи уже существует.');
            return;
        } else {
            $this->resetErrorBag('posName');
        }
        if (GrammaticPartOfSpeech::where('language_id', $this->language->uuid)
            ->where('code', mb_trim($this->posCode))
            ->exists()
        ) {
            $this->addError('posCode', 'Такой код части речи уже существует.');
            return;
        } else {
            $this->resetErrorBag('posCode');
        }

        $pos = new GrammaticPartOfSpeech;
        $pos->name = mb_trim($this->posName);
        $pos->code = mb_trim($this->posCode);
        $pos->description = $this->posDescription ?? null;
        $pos->language_id = $this->language->uuid;
        $pos->order = GrammaticPartOfSpeech::where('language_id', $this->language->uuid)->max('order') + 1;
        $pos->save();

        $this->posName = '';
        $this->posCode = '';
        $this->posDescription = '';
        Flux::modal('add-pos')->close();
    }

    public function setPartOfSpeechTemplate($uuid) {
        $pos = GrammaticPartOfSpeech::find($uuid);
        $this->posName = $pos->name;
        $this->posCode = $pos->code;
        $this->posDescription = $pos->description;
        Flux::modal('add-pos-template')->close();
    }

    public function deletePartOfSpeech($uuid)
    {
        $pos = GrammaticPartOfSpeech::find($uuid);
        if ($pos->language_id !== $this->language->uuid) {
            return;
        }

        $pos->delete();
    }

    #[Renderless]
    public function reorderPartOfSpeech($uuid, $oldIndex, $newIndex)
    {
        $pos = GrammaticPartOfSpeech::findOrFail($uuid);
        if ($pos->language_id !== $this->language->uuid) {
            return;
        }
        $posOther = GrammaticPartOfSpeech::where('language_id', $this->language->uuid)
            ->where('order', $newIndex);
        if ($posOther->exists()) {
            $posOther->update(['order' => $oldIndex]);
        }
        $pos->update(['order' => $newIndex]);
    }

    public function render()
    {
        return view('livewire.languages.grammatic-constructor.pos-tab')
            ->layout('components.layouts.language', ['language' => $this->language]);
    }
}
