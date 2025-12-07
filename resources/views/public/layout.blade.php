
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>MoneyTransfer – Online Money Transfer Platform</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Main landing stylesheet --}}
    <link rel="stylesheet" href="{{ asset('css/public.css') }}">
</head>
<body class="landing-body">

<header class="landing-header">
    <div class="landing-container nav-inner">
        {{-- Logo --}}
        <a href="{{ url('/') }}" class="brand">
            <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-blue-600">💸</div>
            <div class="brand-text">
                <span class="brand-name">Masref</span>
                <span class="brand-tagline">Send money in seconds</span>
            </div>
        </a>

        {{-- Desktop nav --}}
        <nav class="nav-links">
            <a href="#hero">Home</a>
            <a href="#how-it-works">How it works</a>
            <a href="#features">Features</a>
            <a href="#pricing">Pricing</a>
            <a href="#contact">Contact</a>
           
        </nav>

        {{-- Auth buttons --}}
        <div class="nav-actions">
            <a href="{{ route('login') }}" class="btn btn-ghost">Log in</a>
            <a href="{{ url('/register') }}" class="btn btn-primary">Get started</a>
            <a href="{{ route('agent.register') }}" class="btn btn-outline">
                Become an agent
            </a>
        </div>

        {{-- Mobile menu toggle --}}
        <button id="mobileMenuToggle" class="nav-toggle" aria-label="Toggle navigation">
            <span></span><span></span><span></span>
        </button>
    </div>

    {{-- Mobile menu --}}
    <div id="mobileMenu" class="mobile-menu">
        <a href="#hero">Home</a>
        <a href="#how-it-works">How it works</a>
        <a href="#features">Features</a>
        <a href="#pricing">Pricing</a>
        <a href="#contact">Contact</a>
       

        <div class="mobile-menu-actions">
            <a href="{{ route('login') }}" class="btn btn-ghost full">Log in</a>
            <a href="{{ url('/register') }}" class="btn btn-primary full">Get started</a>
            <a href="{{ route('agent.register') }}" class="btn btn-outline full">Become an agent</a>
        </div>
    </div>
</header>

<main>
    @yield('content')
</main>

<footer id="contact" class="landing-footer">
    <div class="landing-container footer-inner">
        <div>
            <h3>MoneyTransfer</h3>
            <p>Modern platform for fast, secure transfers between users and agents.</p>
        </div>
        <div class="footer-columns">
            <div>
                <h4>Product</h4>
                <a href="#how-it-works">How it works</a>
                <a href="#features">Features</a>
                <a href="#pricing">Pricing</a>
            </div>
            <div>
                <h4>For agents</h4>
                <a href="{{ route('agent.register') }}">Become an agent</a>
                <a href="{{ route('agent.login') }}">Agent login</a>
            </div>
            <div>
                <h4>Support</h4>
                <a href="mailto:support@example.com">support@example.com</a>
            </div>
        </div>
    </div>
    <div class="landing-container footer-bottom">
        <span>© {{ date('Y') }} MoneyTransfer. All rights reserved.</span>
    </div>
</footer>

{{-- Main landing script --}}
<script src="{{ asset('js/public.js') }}"></script>
</body>
</html>
