<?php

namespace App\Livewire\Languages;

use App\Models\Language;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Session;
use Livewire\Component;

class IndexPage extends Component
{
    #[Locked]
    public $languages;

    #[Session]
    public bool $my = false;

    public function mount(): void
    {
        $query = Language::limit(10)
            ->orderBy('created_at', 'desc');
        if ($this->my) {
            $query->where('creator_uuid', auth()->id());
        } else {
            $query->where('is_published', true);
        }
        $this->languages = $query->get();
    }

    public function render()
    {
        return view('livewire.languages.index-page');
    }
}
