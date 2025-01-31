<?php

declare(strict_types=1);

namespace App\Livewire\Languages;

use App\Models\Language;
use Illuminate\Contracts\View\Factory;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Locked;
use Livewire\Component;

class IndexPage extends Component
{
    #[Locked]
    public Collection $languages;
    public function __construct(private readonly Factory $viewFactory)
    {
    }

    public function mount(): void
    {
        $this->languages = Language::limit(10)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function render()
    {
        return $this->viewFactory->make('livewire.languages.index-page');
    }
}
