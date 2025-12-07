
@extends('public.layout')

@section('content')
<section id="hero" class="hero-section">
    <div class="landing-container hero-inner">
        {{-- Left side: text and actions --}}
        <div class="hero-copy">
            <p class="hero-eyebrow">Become a part of the new payment revolution</p>
            <h1 class="hero-title">
                Online Money Transfer
                <span>Service Worldwide.</span>
            </h1>
            <p class="hero-subtitle">
                Top up wallets, send to bank accounts, and manage cash out agents
                from a single dashboard. Real time FX, instant notifications, and full
                fraud monitoring.
            </p>

            <div class="hero-actions">
                <a href="{{ url('/register') }}" class="btn btn-primary hero-main-cta">
                    Get started now
                </a>
                <a href="{{ route('login') }}" class="btn btn-ghost hero-secondary-cta">
                    I already have an account
                </a>
            </div>

            <div class="hero-badges">
                <span>KYC ready</span>
                <span>Multi currency FX</span>
                <span>Agent network support</span>
            </div>
        </div>

        {{-- Right side: animated cards --}}
        <div class="hero-visual">
            <div class="card card-main floating-card">
                <div class="card-header">
                    <span class="card-title">Live example</span>
                    <span class="card-chip">Week · Real time</span>
                </div>
                <div class="card-body">
                    <div class="card-row">
                        <span>Sender</span>
                        <span>You – USD wallet</span>
                    </div>
                    <div class="card-row">
                        <span>Receiver</span>
                        <span>Family – LBP cash</span>
                    </div>
                    <div class="card-row">
                        <span>Amount</span>
                        <span>200.00 USD</span>
                    </div>
                    <div class="card-row">
                        <span>Fees</span>
                        <span class="text-success">Promo · 0.00 USD</span>
                    </div>
                    <div class="card-row">
                        <span>Delivered</span>
                        <span>Instant · Real time tracking</span>
                    </div>
                </div>
            </div>

            <div class="card card-side floating-card-delayed">
                <div class="card-label">Debit card balance</div>
                <div class="card-amount">90,989 USD</div>
                <div class="card-note">Updated 2 min ago</div>
            </div>

            <div class="card card-side small floating-card-fast">
                <div class="card-label">Weekly volume</div>
                <div class="card-amount">12,450 USD</div>
                <div class="card-note">Across all corridors</div>
            </div>
        </div>
    </div>
</section>

{{-- How it works --}}
<section id="how-it-works" class="section">
    <div class="landing-container">
        <h2 class="section-title">How it works</h2>
        <div class="steps-grid">
            <div class="step-card">
                <h3>Step 1</h3>
                <h4>Sign up and verify</h4>
                <p>Create your account, complete KYC, and connect a wallet or bank card.</p>
            </div>
            <div class="step-card">
                <h3>Step 2</h3>
                <h4>Add beneficiaries</h4>
                <p>Save trusted receivers for one click transfers in multiple currencies.</p>
            </div>
            <div class="step-card">
                <h3>Step 3</h3>
                <h4>Send and track</h4>
                <p>Send instantly and follow every step from your dashboard in real time.</p>
            </div>
        </div>
    </div>
</section>

{{-- Key features --}}
<section id="features" class="section section-alt">
    <div class="landing-container">
        <h2 class="section-title">Key features</h2>
        <div class="features-grid">
            <div class="feature-card">
                <h4>Real time rates</h4>
                <p>Live FX rates, clear margins, and corridor rules configured from the admin panel.</p>
            </div>
            <div class="feature-card">
                <h4>Admin panel and fraud alerts</h4>
                <p>Approve agents, review refund requests, and act on flagged transactions.</p>
            </div>
            <div class="feature-card">
                <h4>PDF receipts and history</h4>
                <p>Download receipts, export statements, and see every transfer in one place.</p>
            </div>
            <div class="feature-card">
                <h4>Agent wallet and payouts</h4>
                <p>Manage agent balances, cash out requests, and payouts from a single console.</p>
            </div>
        </div>
    </div>
</section>

{{-- Pricing --}}
<section id="pricing" class="section">
    <div class="landing-container">
        <h2 class="section-title">Simple pricing</h2>
        <div class="pricing-grid">
            <div class="pricing-card">
                <h3>For individuals</h3>
                <p class="price-range">0% – 2%</p>
                <p>Low fees depending on corridor and currency.</p>
            </div>
            <div class="pricing-card">
                <h3>For agents</h3>
                <p class="price-range">Custom</p>
                <p>Earn commissions on every transaction you process.</p>
                <a href="{{ route('agent.register') }}" class="pricing-link">
                    Become an agent
                </a>
            </div>
            <div class="pricing-card">
                <h3>For businesses</h3>
                <p class="price-range">Talk to us</p>
                <p>Dedicated limits, support, and reporting for your teams.</p>
                <a href="mailto:sales@example.com" class="pricing-link">
                    Contact sales
                </a>
            </div>
        </div>
    </div>
</section>

{{-- Call to action --}}
<section class="section section-alt cta-section">
    <div class="landing-container cta-inner">
        <div>
            <h2>Ready to launch your next money transfer product.</h2>
            <p>Open your account in minutes and start sending today.</p>
        </div>
        <div class="cta-actions">
            <a href="{{ url('/register') }}" class="btn btn-primary">Create user account</a>
            <a href="{{ route('agent.register') }}" class="btn btn-outline">Become an agent</a>
        </div>
    </div>
</section>
@endsection
