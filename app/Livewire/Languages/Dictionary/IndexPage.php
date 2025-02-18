<?php

namespace App\Livewire\Languages\Dictionary;

use App\Models\Language;
use Livewire\Attributes\Locked;
use Livewire\Component;

class IndexPage extends Component
{
    #[Locked]
    public Language $language;

    public function mount(Language $language): void
    {
        $this->language = $language;
    }

    public function render()
    {
        return view('livewire.languages.dictionary.index-page')
            ->layout('components.layouts.language', ['language' => $this->language]);
    }
}
