<?php

namespace App\Livewire\Languages\Dictionary;

use App\Models\Language;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;

class IndexPage extends Component
{
    use WithPagination;

    #[Locked]
    public Language $language;

    public string $search = '';

    public function mount(Language $language): void
    {
        $this->language = $language;
    }

    public function updatedPage()
    {
        $this->dispatch('scrollTop');
    }

    public function render()
    {
        return view('livewire.languages.dictionary.index-page')
            ->layout('components.layouts.language', ['language' => $this->language]);
    }
}
