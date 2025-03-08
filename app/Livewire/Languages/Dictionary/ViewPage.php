<?php

namespace App\Livewire\Languages\Dictionary;

use App\Models\DictionaryArticle;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ViewPage extends Component
{
    #[Locked]
    public DictionaryArticle $article;

    public function mount(DictionaryArticle $article)
    {
        $this->article = $article;
    }

    public function render()
    {
        return view('livewire.languages.dictionary.view-page')
            ->layout('components.layouts.language', ['language' => $this->article->language]);
    }
}
