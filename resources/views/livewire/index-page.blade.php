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
                    @endif
                </span>

                <span class="text-gray-400">ulid: <span>{{ $user->ulid }}</span></span>
            @endauth
            @guest
                <span>Не авторизованы(</span>
            @endguest
        </div>
    </x-card>
</div>
