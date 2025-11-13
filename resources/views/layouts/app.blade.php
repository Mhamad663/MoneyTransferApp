<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'User Dashboard')</title>

    {{-- Tailwind --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Fonts --}}
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-gray-100 min-h-screen antialiased">

    {{-- NAVBAR (optional minimalist header) --}}
    <nav class="bg-white/80 dark:bg-gray-950/80 backdrop-blur-xl border-b border-gray-200 dark:border-gray-800 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-3 flex justify-between items-center">
            <a href="/" class="text-lg font-semibold text-indigo-600 dark:text-indigo-400">MyApp</a>
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-600 dark:text-gray-400">{{ auth()->user()->name ?? 'Guest' }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="text-sm text-gray-600 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-300">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    {{-- MAIN CONTENT --}}
    <main class="pt-8 pb-16 px-4 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="border-t border-gray-200 dark:border-gray-800 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
        &copy; {{ date('Y') }} MyApp. All rights reserved.
    </footer>

</body>
</html>
