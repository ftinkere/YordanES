<?php

declare(strict_types=1);

namespace App\Livewire\Languages\Dictionary;

use App\Models\Language;
use Illuminate\Contracts\View\Factory;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ViewPage extends Component
{
    #[Locked]
    public Language $language;
    public function __construct(private readonly Factory $viewFactory)
    {
    }

    public function mount(Language $language): void
    {
        $this->language = $language;
    }

    public function render()
    {
        return $this->viewFactory->make('livewire.languages.dictionary.view-page')
            ->layout('components.layouts.language', ['language' => $this->language]);
    }
}
