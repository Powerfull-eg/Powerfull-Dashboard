{{-- <x-layouts::scaffold> --}}
    {{-- @yield('content') --}}
{{-- </x-layouts::scaffold> --}}
<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name') ?? "PDF-Document"}}</title>

    {{-- Tabler Core --}}
    <style>
        {{ file_get_contents(public_path("/vendor/tabler/tabler.ltr.min.css")) }}
        {{ file_get_contents(public_path("/vendor/tabler/tabler-vendors.min.css")) }}
        {{ file_get_contents(public_path("/vendor/tabler-icons/tabler-icons.min.css")) }}
        {{ file_get_contents(public_path("/vendor/bootstrap/bootstrap.min.css")) }}
        {{ file_get_contents(public_path("assets/css/app.css")) }}
        {{ file_get_contents(public_path("assets/css/overrides.css")) }}
    </style>

    @stack('styles')

</head>
<body>
    @yield('content')
</body>

</html>