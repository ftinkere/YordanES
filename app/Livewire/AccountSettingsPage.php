<?php

namespace App\Livewire;

use App\Aggregates\UserAggregate;
use App\Models\User;
use App\Services\UserService;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class AccountSettingsPage extends Component
{
    #[Locked]
    public User $user;

    public string $username;
    public string $name;
    public string $email;

    public function mount()
    {
        $user = auth()->user();
        if (! $user) {
            $this->redirect('/login');
            return;
        }
        $this->user = $user;

        $this->username = $user->username;
        $this->name = $user->name;
        $this->email = $user->email;
    }

    #[On('apply-setting')]
    public function changeAttribute(string $attribute)
    {
        $userAggregate = UserAggregate::retrieve($this->user->uuid);
        switch ($attribute) {
            case 'username':
                $userAggregate->changeUsername($this->username);
                break;
            case 'name':
                $userAggregate->changeName($this->name);
                break;
            case 'email':
                $userAggregate->changeEmail($this->email);
                break;
        }
        $userAggregate->persist();
        $this->user = auth()->user();
    }

    public function resendEmailConfirmation(UserService $service): void
    {
        $service->sendConfirmationEmail($this->user);
    }

    public function render()
    {
        return view('livewire.account-settings-page');
    }
}
