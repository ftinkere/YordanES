<?php

namespace App\Livewire\Components;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Navbar extends Component
{
    public function logout()
    {
        $user = Auth::user();
        if (! $user) {
            return;
        }
        $user->logout();
        $this->redirect('/');
    }

    public function render()
    {
        return view('livewire.components.navbar');
    }
}
