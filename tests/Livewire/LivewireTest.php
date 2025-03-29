<?php

use App\Aggregates\UserRepositoryAggregate;
use App\Livewire\AccountSettingsPage;
use Illuminate\Foundation\Testing\RefreshDatabase;

pest()->extend(Tests\TestCase::class);
uses(RefreshDatabase::class);

describe('Открывается страница', function () {
    beforeEach(function () {
        $userRepository = app(UserRepositoryAggregate::class);
        $user = $userRepository->register('test', 'Test', 'test@yordan.ru', Hash::make('password'))
            ->persist()
            ->model();
        $this->actingAs($user);
    });

    test('Настройки профиля', function () {
        Livewire::test(AccountSettingsPage::class)
            ->call('changeAttribute');
    });
});