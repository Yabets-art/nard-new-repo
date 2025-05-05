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
                <div class="text-center mb-5">
                    <a href="/" class="inline-block">
                        <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                    </a>
                    <h1 class="auth-page-title text-2xl mt-3 animate__animated animate__fadeIn animate__delay-1s">Nard Candles</h1>
                    <p class="text-candle-shadow mt-1 animate__animated animate__fadeIn animate__delay-1s">Admin Dashboard</p>
                </div>
            </x-slot>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />

            <div class="animate__animated animate__fadeIn animate__delay-1s">
                <div class="text-center mb-6">
                    <p class="text-candle-shadow">This is an admin-only system. Registration is not available.</p>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <a class="underline text-sm text-candle-shadow hover:text-candle-flame transition duration-150 ease-in-out" href="{{ route('login') }}">
                        Go to Login Page
                    </a>
                </div>
            </div>
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
