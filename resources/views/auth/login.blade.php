<x-guest-layout>
            <div class="w-full px-6 py-4 mt-6 overflow-hidden bg-white shadow-md sm:max-w-md sm:rounded-lg">
            <div class="flex justify-center mb-6">
                <x-application-logo class="w-20 h-20" />
            </div>
            
            <h2 class="text-3xl font-extrabold text-center text-gray-900 mb-6">
                {{ __('Log in to continue') }}
            </h2>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-4">
                    <x-input-label for="email" :value="__('Email address')" />
                    <x-text-input id="email" class="block mt-1 w-full rounded-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Email address" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full rounded-full"
                                    type="password"
                                    name="password"
                                    required autocomplete="current-password"
                                    placeholder="Password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="block mb-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-300 focus:ring focus:ring-green-200 focus:ring-opacity-50" name="remember">
                        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-between mb-4">
                    <x-primary-button class="w-full justify-center py-3 rounded-full bg-green-500 hover:bg-green-600">
                        {{ __('Log In') }}
                    </x-primary-button>
                </div>

                @if (Route::has('password.request'))
                    <div class="text-center">
                        <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    </div>
                @endif
            </form>

            <hr class="my-6 border-t border-gray-300">

            <div class="text-center">
                <p class="text-sm text-gray-600">{{ __("Don't have an account?") }}</p>
                <a href="{{ route('register') }}" class="mt-2 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-full font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                    {{ __('Sign up for free') }}
                </a>
            </div>
        </div>
</x-guest-layout>
