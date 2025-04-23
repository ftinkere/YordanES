<?php

namespace App\Livewire\Languages;

use App\Aggregates\LanguageAggregate;
use App\Models\Language;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

class UpdatePage extends Component
{
    #[Locked]
    public Language $language;

    #[Validate('required|filled')]
    public string $name;
    #[Validate('required|filled')]
    public ?string $autoname;
    #[Validate('required|filled')]
    public ?string $autoname_transcription;

    #[Validate('required|boolean')]
    public bool $public;

    public string $about;

    public function mount(Language $language): void
    {
        $this->language = $language;

        $this->name = $this->language->name;
        $this->autoname = $this->language->autoname;
        $this->autoname_transcription = $this->language->autoname_transcription;
        $this->about = $this->language->description('about')?->description ?? '';
        $this->public = $this->language->is_published;
    }

    public function updateLanguage(): void
    {
        $language = Language::findOrFail($this->language->uuid);
        $language->name = $this->name;
        $language->setAutoname($this->autoname, $this->autoname_transcription);
        $language->setDescription('about', $this->about);
        $language->is_published = $this->public;

        $language->save();

        $this->mount($language);

        $this->redirect('/languages/' . $language->uuid);
    }

    public function render()
    {
        return view('livewire.languages.update-page')
            ->layout('components.layouts.language', ['language' => $this->language]);
    }
}
