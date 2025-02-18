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

    public string $about;

    public function mount(Language $language): void
    {
        $this->language = $language;

        $this->name = $this->language->name;
        $this->autoname = $this->language->autoname;
        $this->autoname_transcription = $this->language->autoname_transcription;
        $this->about = $this->language->description('about');
    }

    public function updateLanguage(): void
    {
        $language = LanguageAggregate::retrieve($this->language->uuid);
        if ($this->name !== $language->name) {
            $language->setName($this->name);
        }
        if ($this->autoname !== $language->autoname || $this->autoname_transcription !== $language->autoname_transcription) {
            $language->setAutoname($this->autoname, $this->autoname_transcription);
        }

        if ($this->language->description('about') !== $this->about) {
            $language->setDescription('about', $this->about);
        }

        $language->persist();

        $this->mount(Language::findOrFail($language->uuid));

        $this->redirect('/languages/' . $language->uuid);
    }

    public function render()
    {
        return view('livewire.languages.update-page')
            ->layout('components.layouts.language', ['language' => $this->language, 'editable' => true]);
    }
}
