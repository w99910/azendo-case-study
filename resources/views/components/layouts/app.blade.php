<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'App' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">

    @livewireStyles
</head>

<body class="bg-gray-100 font-[Inter] h-screen flex flex-col overflow-hidden w-screen ">
    <nav class="bg-blue-900 text-white p-4">
        <div class="container mx-auto flex justify-between">
            <a href="/" class="text-xl font-bold">Azendo</a>
        </div>
    </nav>
    {{ $slot }}
    @livewireScripts

    <div x-data="{
        show: false,
        message: '',
        type: 'success',
        timeout: null,
        showToast(event) {
            this.message = event.detail.message;
            this.type = event.detail.type || 'success';
            this.show = true;
            clearTimeout(this.timeout);
            this.timeout = setTimeout(() => this.show = false, 3000);
        }
    }" x-init="window.addEventListener('showtoast', e => showToast(e))" x-show="show"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-10"
        x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-10"
        class="fixed top-4 right-4 z-50" style="min-width: 250px;">
        <div :class="{
            'bg-green-400 text-black': type === 'success',
            'bg-blue-400 text-black': type === 'info',
            'bg-red-400 text-black': type === 'error',
            'bg-yellow-400 text-black': type === 'warning'
        }"
            class=" px-4 py-2 rounded justify-between shadow flex items-center gap-2">
            <span x-text="message"></span>
            <button @click="show = false" class="ml-2 cursor-pointer ">&times;</button>
        </div>
    </div>

    @if (session('message'))
        <script>
            window.addEventListener('DOMContentLoaded', function() {
                window.dispatchEvent(new CustomEvent('showtoast', {
                    detail: {
                        type: '{{ session('type') ?? 'info' }}',
                        message: '{{ session('message') }}'
                    }
                }));
            });
        </script>
    @endif
</body>

</html>
