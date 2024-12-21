<div>
    <x-card title="Вы" rounded="3xl" class="border max-w-md mx-auto">
        <div class="flex flex-col ">
            @auth
                <span>{{ $user->visible_name }} (<span>{{ $user->username }}</span>)</span>

                <span>
                    Почта: <span>{{ $user->email }}</span>
                    @if($user->email_verified_at)
                        <x-icon name="check" class="h-4 inline text-green-600" />
                    @else
                        <x-icon name="x-mark" class="h-4 inline text-red-600" />
                        <x-button
                                id="resend-confirmation-btn"
                                flat
                                wire:click="resendEmailConfirmation()"
                                x-data="{ show: true }"
                                x-show="show"
                                x-on:click="show = false"
                        >Переотправить</x-button>
                    @endif
                </span>

                <span class="text-gray-400">uuid: <span>{{ $user->uuid }}</span></span>
            @endauth
            @guest
                <span>Не авторизованы(</span>
            @endguest
        </div>
    </x-card>
</div>
