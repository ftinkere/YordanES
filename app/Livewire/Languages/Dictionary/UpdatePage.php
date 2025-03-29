<?php

namespace App\Livewire\Languages\Dictionary;

use App\Models\DictionaryArticle;
use App\Models\Lexeme;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

class UpdatePage extends Component
{
    #[Locked]
    public DictionaryArticle $dictionaryArticle;

    #[Validate('required')]
    public string $vocabula;
    public ?string $transcription = null;
    public ?string $adaptation = null;

    public string $article;

    public array $lexemes;

    public function mount(DictionaryArticle $article)
    {
        $this->dictionaryArticle = $article;

        $this->vocabula = $article->vocabula;
        $this->transcription = $article->transcription;
        $this->adaptation = $article->adaptation;

        $this->article = $article->article;

        foreach ($article->lexemes as $lexeme) {
            $this->lexemes[$lexeme->order][$lexeme->suborder] = [
                'uuid' => $lexeme->uuid,
                'group' => $lexeme->group,
                'short' => $lexeme->short,
                'full' => $lexeme->full,
            ];
        }
    }

    public function updateArticle()
    {
        $this->validate();

        $article = DictionaryArticle::findOrFail($this->dictionaryArticle->uuid);

        $article->update([
            'vocabula' => $this->vocabula,
            'transcription' => $this->transcription,
            'adaptation' => $this->adaptation,
            'article' => $this->article,
        ]);

        foreach ($this->lexemes as $order => $lexemesOrder) {
            foreach ($lexemesOrder as $suborder => $lexemeArray) {
                if (isset($lexemeArray['uuid'])) {
                    $lexeme = Lexeme::findOrFail($lexemeArray['uuid']);
                    $lexeme->update([
                        'group' => $lexemeArray['group'],
                        'short' => $lexemeArray['short'],
                        'full' => $lexemeArray['full'],
                    ]);
                } else {
                    Lexeme::create([
                        'language_uuid' => $this->dictionaryArticle->language_uuid,
                        'article_uuid' => $this->dictionaryArticle->uuid,
                        'group' => $lexemeArray['group'],
                        'order' => $order,
                        'suborder' => $suborder,
                        'short' => $lexemeArray['short'],
                        'full' => $lexemeArray['full'],
                    ]);
                }
            }
        }

        return redirect()->route('languages.dictionary.view', ['language' => $this->dictionaryArticle->language, 'article' => $this->dictionaryArticle]);
    }

    public function render()
    {
        return view('livewire.languages.dictionary.update-page')
            ->layout('components.layouts.language', ['language' => $this->dictionaryArticle->language]);
    }
}
