<div>
    <x-alert id="flash-message" info @class([
        'from-top py-2',
//        'from-top-opened' => session()->has('message'),
        'from-top-opened',
        'rounded-t-none',
    ])>
        <x-slot name="title" id="flash-message-content">
{{--            {{ session('message') }}--}}
            Message
        </x-slot>

        <x-slot name="action">
            <x-mini-button flat rose class="p-0.5" x-on:click="Livewire.dispatch('flash-message-close')">
                <x-icon name="x-mark" mini />
            </x-mini-button>
        </x-slot>
    </x-alert>
</div>

@script
<script>
    Livewire.on('flash-message', (text) => {
        const flashContent = document.getElementById('flash-message-content');
        const flash = document.getElementById('flash-message');

        flashContent.innerHTML = text;
        flash.classList.add('from-top-opened')
    })

    Livewire.on('flash-message-close', () => {
        const flash = document.getElementById('flash-message');
        flash.classList.remove('from-top-opened')
    })

    setTimeout(function () {
        const flash = document.getElementById('flash-message');
        flash.classList.remove('from-top-opened')
    }, 15000)

</script>
@endscript