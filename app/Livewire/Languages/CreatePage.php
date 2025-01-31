<?php

declare(strict_types=1);

namespace App\Livewire\Languages;

use App\Aggregates\LanguageAggregate;
use App\Models\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\View\Factory;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreatePage extends Component
{
    #[Validate('required|filled')]
    public $name;
    public function __construct(private readonly Guard $guard, private readonly Factory $viewFactory)
    {
    }

    #[Validate('required|filled')]
    public $autoname;

    #[Validate('required|filled')]
    public $autoname_transcription;

    public function createLanguage(): void
    {
        /** @var User $user */
        $user = $this->guard->user();

        new LanguageAggregate()
            ->create($this->name, $user->uuid)
            ->setAutoname($this->autoname, $this->autoname_transcription)
            ->persist();

        $this->redirect('/languages');
    }

    public function render()
    {
        return $this->viewFactory->make('livewire.languages.create-page');
    }
}
