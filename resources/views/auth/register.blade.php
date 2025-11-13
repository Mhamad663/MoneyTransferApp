<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
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
