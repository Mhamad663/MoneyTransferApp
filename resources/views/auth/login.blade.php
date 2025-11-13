<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
        <div class="mt-6">
  <div class="relative">
    <div class="absolute inset-0 flex items-center"><span class="w-full border-t"></span></div>
    <div class="relative flex justify-center text-sm"><span class="bg-white px-2 text-gray-500">Or continue with</span></div>
  </div>

  <div class="mt-4 grid grid-cols-2 gap-3">
    <a href="{{ route('google.redirect') }}" class="inline-flex w-full items-center justify-center rounded-md border px-4 py-2 text-sm font-medium hover:bg-gray-50">
      <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" class="h-5 w-5 mr-2" alt=""> Google
    </a>
    <a href="{{ route('facebook.redirect') }}" class="inline-flex w-full items-center justify-center rounded-md border px-4 py-2 text-sm font-medium hover:bg-gray-50">
      <svg class="h-5 w-5 mr-2" viewBox="0 0 24 24" fill="currentColor"><path d="M22 12a10 10 0 1 0-11.6 9.9v-7h-2.4V12h2.4V9.7c0-2.4 1.4-3.8 3.6-3.8 1 0 2 .2 2 .2v2.2h-1.1c-1.1 0-1.5.7-1.5 1.4V12h2.6l-.4 2.9h-2.2v7A10 10 0 0 0 22 12z"/></svg>
      Facebook
    </a>
  </div>
</div>

    </form>
</x-guest-layout>
