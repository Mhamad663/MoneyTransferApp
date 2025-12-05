<nav class="bg-slate-900 border-b border-slate-800 p-4">
    <div class="max-w-7xl mx-auto flex justify-between">
        <div class="text-lg font-semibold">Admin Panel</div>

        <div>
            <a href="{{ route('admin.dashboard') }}" class="text-gray-300 hover:text-white mx-2">Dashboard</a>
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               class="text-gray-300 hover:text-white mx-2">
                Logout
            </a>
            <form id="logout-form" method="POST" action="{{ route('logout') }}">
                @csrf
            </form>
        </div>
    </div>
</nav>
