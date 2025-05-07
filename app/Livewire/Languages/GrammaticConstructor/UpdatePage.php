<?php

namespace App\Livewire\Languages\GrammaticConstructor;

use App\Models\Language;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\Component;

class UpdatePage extends Component
{
    #[Locked]
    public Language $language;

    #[Url]
    public string $tab = 'pos';


    public function mount(Language $language) {
        $this->language = $language;
    }
    public function render()
    {
        return view('livewire.languages.grammatic-constructor.update-page')
            ->layout('components.layouts.language', ['language' => $this->language]);
    }
}
