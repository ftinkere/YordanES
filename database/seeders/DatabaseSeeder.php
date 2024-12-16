<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Uid\Ulid;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::register('admin', 'Админ', 'admin@yordan.ru', Hash::make('password'));
        $admin->verifyEmail();
    }
}
