<?php

namespace App\Livewire\Languages;

use App\Models\Language;
use Livewire\Attributes\Locked;
use Livewire\Component;

class IndexPage extends Component
{
    #[Locked]
    public $languages;

    public function mount(): void
    {
        $this->languages = Language::limit(10)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.languages.index-page');
    }
}
