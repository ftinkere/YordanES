<?php

namespace App\Livewire\Languages\Dictionary;

use App\Aggregates\LanguageAggregate;
use App\Models\Language;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreatePage extends Component
{
    #[Locked]
    public Language $language;

    #[Validate('required')]
    public string $vocabula;
    public string $transcription;
    public string $adaptation;

    #[Validate('required')]
    public string $short;
    public string $full;

    public array $lexemes = []; // [*order* => [*suborder* => [lexeme] ] ]


    public function mount(Language $language): void
    {
        $this->language = $language;
    }

    public function createArticle()
    {
        $articleAggregate = LanguageAggregate::retrieve($this->language->uuid)
            ->dictionary()
            ->createArticleFull($this->vocabula, $this->transcription ?? '', $this->adaptation, $this->short, $this->full ?? '', $this->lexemes)
            ->persist();

        $this->redirect("/languages/{$this->language->uuid}/dictionary/{$articleAggregate->uuid}");
    }

    public function render()
    {
        return view('livewire.languages.dictionary.create-page')
            ->layout('components.layouts.language', ['language' => $this->language]);
    }
}
