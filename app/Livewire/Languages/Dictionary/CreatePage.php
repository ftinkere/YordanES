<?php

namespace App\Livewire\Languages\Dictionary;

use App\Aggregates\LanguageAggregate;
use App\Models\Language;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Throwable;

class CreatePage extends Component
{
    #[Locked]
    public Language $language;

    #[Validate('required')]
    public string $vocabula;
    public string $transcription;
    public string $adaptation;

    public string $article;

    public array $lexemes = [[ ['group' => 1, 'short' => '', 'full' => ''] ]]; // [*order* => [*suborder* => [lexeme] ] ]


    public function mount(Language $language): void
    {
        $this->language = $language;
    }

    /**
     * @throws Throwable
     */
    public function createArticle()
    {
        $this->validate();

        $articleAggregate = Language::findOrFail($this->language->uuid)
            ->createArticle($this->vocabula, $this->transcription ?? '', $this->adaptation ?? '', $this->article ?? '', $this->lexemes);

        $this->redirect("/languages/{$this->language->uuid}/dictionary/{$articleAggregate->uuid}");
    }

    public function render()
    {
        return view('livewire.languages.dictionary.create-page')
            ->layout('components.layouts.language', ['language' => $this->language]);
    }
}
