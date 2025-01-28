<?php

namespace App\Livewire\Languages;

use App\Aggregates\LanguageAggregate;
use App\Livewire\Traits\XsampaToIpa;
use App\Models\User;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreatePage extends Component
{
    #[Validate('required|filled')]
    public $name;
    #[Validate('required|filled')]
    public $autoname;
    #[Validate('required|filled')]
    public $autoname_transcription;

    public function createLanguage()
    {
        /** @var User $user */
        $user = auth()->user();

        new LanguageAggregate()
            ->create($this->name, $user->uuid)
            ->setAutoname($this->autoname, $this->autoname_transcription)
            ->persist();

        $this->redirect('/languages');
    }

    public function render()
    {
        return view('livewire.languages.create-page');
    }
}
