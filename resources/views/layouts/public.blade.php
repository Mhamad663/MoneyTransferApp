{{-- resources/views/layouts/public.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>MoneyTransfer</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background: #020617; /* slate-950 */
            color: #e5e7eb;      /* slate-200 */
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .nav-blur {
            background: linear-gradient(
                to right,
                rgba(15, 23, 42, 0.96),
                rgba(15, 23, 42, 0.9)
            );
            backdrop-filter: blur(18px);
        }

        .nav-link {
            font-size: 0.875rem;
            color: #cbd5f5; /* soft slate */
            transition: color 0.15s ease, opacity 0.15s ease;
        }
        .nav-link:hover {
            color: #ffffff;
        }

        .nav-cta-primary {
            font-size: 0.85rem;
            padding: 0.45rem 1rem;
            border-radius: 9999px;
            background: linear-gradient(90deg, #0ea5e9, #22c55e);
            color: #0b1120;
            font-weight: 600;
            box-shadow: 0 10px 25px rgba(15, 118, 210, 0.35);
        }
        .nav-cta-primary:hover {
            filter: brightness(1.06);
        }

        .nav-cta-outline {
            font-size: 0.85rem;
            padding: 0.45rem 1rem;
            border-radius: 9999px;
            border: 1px solid rgba(148, 163, 184, 0.6);
            color: #e5e7eb;
        }
        .nav-cta-outline:hover {
            background: rgba(15, 23, 42, 0.9);
        }

        .btn-gradient {
            background: linear-gradient(90deg, #0ea5e9, #22c55e);
            color: #0b1120;
            font-weight: 600;
            box-shadow: 0 15px 35px rgba(8, 47, 73, 0.6);
        }

        .form-card {
            background: radial-gradient(circle at top left, #0f172a, #020617);
            border: 1px solid rgba(30, 64, 175, 0.55);
            box-shadow:
                0 18px 45px rgba(15, 23, 42, 0.9),
                0 0 0 1px rgba(15, 23, 42, 0.9);
        }

        .input {
            background-color: rgba(15, 23, 42, 0.85);
            border: 1px solid rgba(51, 65, 85, 0.9);
            color: #e5e7eb;
        }
        .input:focus {
            outline: none;
            border-color: #0ea5e9;
            box-shadow: 0 0 0 1px rgba(14, 165, 233, 0.5);
        }

        .fade-in {
            opacity: 0;
            transform: translateY(12px);
            animation: fadeInUp 0.55s ease-out forwards;
        }
        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* mobile menu helper */
        .mobile-link {
            display: block;
            padding: 0.25rem 0;
            font-size: 0.875rem;
            color: #cbd5f5;
        }
        .mobile-link:hover {
            color: #ffffff;
        }
    </style>
</head>
<body class="antialiased">

    {{-- Shared navbar for ALL public, auth & agent-auth pages --}}
    @include('partials.public-navbar')

    <main class="pt-20">
        @yield('content')
    </main>

    <script>
        const btn  = document.getElementById('mobileMenuBtn');
        const menu = document.getElementById('mobileMenu');
        if (btn && menu) {
            btn.addEventListener('click', () => {
                menu.classList.toggle('hidden');
            });
        }
    </script>
</body>
</html>
