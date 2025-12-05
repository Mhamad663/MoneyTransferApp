{{-- resources/views/components/admin-layout.blade.php --}}
@props(['header'])

<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin • {{ config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-slate-950 text-gray-100">

    {{-- ADMIN NAVBAR (optional) --}}
    @include('admin.partials.navbar')

    {{-- Header slot --}}
    @if(isset($header))
        <header class="bg-slate-900 border-b border-slate-800 p-6 shadow">
            <div class="max-w-7xl mx-auto">
                {{ $header }}
            </div>
        </header>
    @endif

    <main>
        {{ $slot }}
    </main>

</body>
</html>
