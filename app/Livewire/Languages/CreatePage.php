<?php

namespace App\Livewire\Languages;

use App\Models\Language;
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

    public function createLanguage(): void
    {
        /** @var User $user */
        $user = auth()->user();

        $language = Language::create($user, $this->name)
            ->setAutoname($this->autoname, $this->autoname_transcription);
        $language->save();

        $this->redirect('/languages/' . $language->uuid);
    }

    public function render()
    {
        return view('livewire.languages.create-page');
    }
}
