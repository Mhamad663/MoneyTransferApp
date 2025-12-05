<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin • {{ config('app.name','Masref') }}</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="h-full bg-slate-950 text-slate-50 antialiased">
    {{-- The page itself (full screen) --}}
    @yield('content')
</body>
</html>

