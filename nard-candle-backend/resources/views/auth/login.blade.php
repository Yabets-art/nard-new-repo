<x-guest-layout>
    <div class="candle-card-wrapper">
        <div class="candle-glow"></div>
        <div class="loading-spinner"></div>
        
        <x-auth-card class="candle-card animate__animated animate__fadeIn">
            @if(session('error'))
                <div class="bg-red-500 text-white p-2 mb-4 rounded animate__animated animate__headShake">
                    {{ session('error') }}
                </div>
            @endif
            
            <x-slot name="logo">
                <div class="text-center mb-8">
                    <a href="/" class="inline-block">
                        <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                    </a>
                    <h1 class="auth-page-title text-2xl mt-3 animate__animated animate__fadeIn animate__delay-1s text-white">Nard Candles</h1>
                    <p class="text-candle-shadow mt-1 animate__animated animate__fadeIn animate__delay-1s">Admin Dashboard</p>
                </div>
            </x-slot>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />

            <form method="POST" action="{{ route('login') }}" class="animate__animated animate__fadeIn animate__delay-1s">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-label for="email" :value="__('Email')" class="label-candle" />

                    <x-input id="email" class="block mt-1 w-full input-candle"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required autofocus 
                        placeholder="Enter your email address" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-label for="password" :value="__('Password')" class="label-candle" />

                    <x-input id="password" class="block mt-1 w-full input-candle"
                        type="password"
                        name="password"
                        required autocomplete="current-password" 
                        placeholder="Enter your password" />
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-candle-flame shadow-sm focus:border-candle-flame focus:ring focus:ring-candle-glow focus:ring-opacity-50" name="remember">
                        <span class="ml-2 text-sm text-candle-shadow">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-8">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-candle-shadow hover:text-candle-flame transition duration-150 ease-in-out" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                    <button type="submit" class="ml-3 inline-flex items-center px-4 py-2 bg-candle-flame border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-candle-glow focus:outline-none focus:border-candle-glow focus:ring ring-candle-glow ring-opacity-50 active:bg-candle-flame disabled:opacity-25 transition ease-in-out duration-150 btn-candle">
                        {{ __('Log in') }}
                    </button>
                </div>
            </form>
        </x-auth-card>
    </div>
    
    <style>
        .text-candle-shadow {
            color: var(--candle-shadow);
        }
        
        .text-candle-flame {
            color: var(--candle-flame);
        }
        
        .text-candle-glow {
            color: var(--candle-glow);
        }
        
        .bg-candle-flame {
            background-color: var(--candle-flame);
        }
        
        .bg-candle-glow {
            background-color: var(--candle-glow);
        }
        
        .border-candle-flame {
            border-color: var(--candle-flame);
        }
        
        .border-candle-glow {
            border-color: var(--candle-glow);
        }
        
        .ring-candle-glow {
            --tw-ring-color: var(--candle-glow);
        }
        
        .focus\:border-candle-flame:focus {
            border-color: var(--candle-flame);
        }
        
        .focus\:border-candle-glow:focus {
            border-color: var(--candle-glow);
        }
        
        .focus\:ring-candle-glow:focus {
            --tw-ring-color: var(--candle-glow);
        }
        
        .hover\:text-candle-flame:hover {
            color: var(--candle-flame);
        }
        
        .hover\:bg-candle-glow:hover {
            background-color: var(--candle-glow);
        }
        
        .active\:bg-candle-flame:active {
            background-color: var(--candle-flame);
        }
    </style>
</x-guest-layout>
