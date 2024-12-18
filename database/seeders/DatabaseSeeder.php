<?php

namespace Database\Seeders;

use App\Aggregates\UserAggregate;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = new UserAggregate();
        $admin
            ->register('admin', 'Админ', 'admin@yordan.ru', Hash::make('password'))
            ->verifyEmail()
            ->persist()
        ;
    }
}
