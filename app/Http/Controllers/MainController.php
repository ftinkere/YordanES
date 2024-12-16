<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (! $user) {
            return 'No auth';
        }

        return $user->username.' '.$user->email_verified_at?->format('Y-m-d H:i:s');
    }
}
