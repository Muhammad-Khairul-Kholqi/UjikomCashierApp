<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    {{-- header --}}
    @include('utils.admin.header')

    {{-- navbar --}}
    @include('utils.admin.navbar')

    {{-- main content --}}
    <div class="flex justify-center">
        <div class="w-full max-w-[1500px]">
            <main class="p-5">
                @yield('content-admin')
            </main>
        </div>
    </div>
</body>
</html>
