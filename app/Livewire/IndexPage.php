<?php

namespace App\Livewire;

use App\Events\UserEmailChanged;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Component;

class IndexPage extends Component
{
    #[Locked]
    public ?User $user;

    public function mount(): void
    {
        $this->user = Auth::user();
    }

    public function resendEmailConfirmation(): void
    {
        event(new UserEmailChanged($this->user->uuid));
    }

    public function render()
    {
        return view('livewire.index-page');
    }
}
