<?php

namespace App\Livewire;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Locked;
use Livewire\Component;

class IndexPage extends Component
{
    #[Locked]
    public ?User $user;

    public function resendEmailConfirmation(UserService $service)
    {
        if ($this->user) {
            $service->sendConfirmationEmail($this->user);
        }
    }

    public function mount()
    {
        $this->user = Auth::user();
    }

    public function render()
    {
        return view('livewire.index-page');
    }
}
