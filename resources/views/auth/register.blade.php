<x-guest-layout>
        <div class="w-full px-6 py-4 mt-6 overflow-hidden bg-white shadow-md sm:max-w-md sm:rounded-lg">
            <div class="flex justify-center mb-6">
                <x-application-logo class="w-20 h-20" />
            </div>
            
            <h2 class="text-3xl font-extrabold text-center text-gray-900 mb-6">
                {{ __('Create your account') }}
            </h2>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="mb-4">
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" class="block mt-1 w-full rounded-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div class="mb-4">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full rounded-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="Email address" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full rounded-full"
                                    type="password"
                                    name="password"
                                    required autocomplete="new-password"
                                    placeholder="Password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full rounded-full"
                                    type="password"
                                    name="password_confirmation" required autocomplete="new-password"
                                    placeholder="Confirm password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between mb-4">
                    <x-primary-button class="w-full justify-center py-3 rounded-full bg-green-500 hover:bg-green-600">
                        {{ __('Register') }}
                    </x-primary-button>
                </div>
            </form>

            <hr class="my-6 border-t border-gray-300">

            <div class="text-center">
                <p class="text-sm text-gray-600">{{ __('Already have an account?') }}</p>
                <a href="{{ route('login') }}" class="mt-2 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-full font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                    {{ __('Log in') }}
                </a>
            </div>
        </div>
</x-guest-layout>
