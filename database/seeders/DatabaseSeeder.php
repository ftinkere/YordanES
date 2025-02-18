<?php

namespace Database\Seeders;

use App\Aggregates\LanguageAggregate;
use App\Aggregates\UserAggregate;
use App\Aggregates\UserRepositoryAggregate;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (! User::where('username', 'admin')->exists()) {
            $repository = app(UserRepositoryAggregate::class);
            /** @var UserAggregate $admin */
            $repository
                ->register('admin', 'Админ', 'admin@yordan.ru', Hash::make('password'))
                ->verifyEmail()
                ->persist();
        }
        for ($i = 0; $i < 10; $i++) {
            $language = app(LanguageAggregate::class);
            /** @var LanguageAggregate $language */
            $language
                ->create('lang-' . $i, User::where('username', 'admin')->first()->uuid)
                ->persist();
        }
    }
}
