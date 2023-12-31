<!DOCTYPE html>
<html x-data="data" lang="en">
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <!-- Scripts -->
        <script src="{{ asset('js/init-alpine.js') }}"></script>
        <script src="{{ asset("assets/sweetalert2/dist/sweetalert2.all.min.js") }}"></script>
        <link rel="stylesheet" href="{{ asset("assets/sweetalert2/dist/sweetalert2.min.css") }}">
        @stack('style')
        @livewireStyles
</head>
<body>
<div
    class="flex h-screen bg-gray-50"
    :class="{ 'overflow-hidden': isSideMenuOpen }"
>
    <!-- Desktop sidebar -->
    @include('layouts.navigation')
    <!-- Mobile sidebar -->
    <!-- Backdrop -->
    @include('layouts.navigation-mobile')
    <div class="flex flex-col flex-1 w-full">
        @include('layouts.top-menu')
        <main class="h-full overflow-y-auto">
            <div class="container px-6 mx-auto grid">
                @if (isset($header))
                    <h2 class="my-6 text-2xl font-semibold text-gray-700">
                        {{ $header }}
                    </h2>
                @endif

                {{ $slot }}
            </div>
        </main>
    </div>
</div>
@livewireScripts
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
@stack('script')
<script>
    /* Preview Image Script */
    let gambar_andalan_konten_input = document.getElementById("gambar_andalan_konten");
    gambar_andalan_konten_input.addEventListener("change", function()
    {
        let file = gambar_andalan_konten_input.files[0];
        let reader = new FileReader();
        reader.addEventListener("load", function()
        {
            let image_source = document.getElementById("preview");
            image_source.src = reader.result;
        });

        reader.readAsDataURL(file);
    });
</script>
</body>
</html>
