<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Nard Candles</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>
<body class="bg-gray-900">
    <div class="candle-card-wrapper">
        <div class="candle-glow"></div>
        <div class="loading-spinner"></div>
        
        <div class="candle-card animate__animated animate__fadeIn">
            <div class="text-center mb-8">
                <a href="/" class="inline-block">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
                <h1 class="auth-page-title text-2xl mt-3 animate__animated animate__fadeIn animate__delay-1s text-white">Nard Candles</h1>
                <p class="text-candle-shadow mt-1 animate__animated animate__fadeIn animate__delay-1s">Admin Dashboard</p>
            </div>
            
            <div class="text-center mb-8">
                <p class="text-candle-shadow">Welcome to Nard Candles Admin Dashboard</p>
            </div>

            <div class="flex items-center justify-center mt-8">
                <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-candle-flame border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-candle-glow focus:outline-none focus:border-candle-glow focus:ring ring-candle-glow ring-opacity-50 active:bg-candle-flame disabled:opacity-25 transition ease-in-out duration-150 btn-candle">
                    Login to Dashboard
                </a>
            </div>
        </div>
    </div>
    
    <style>
        :root {
            --candle-shadow: #9ca3af;
            --candle-flame: #4a6741;
            --candle-glow: #5a7a51;
        }
        
        .candle-card-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
        }
        
        .candle-card {
            background: #1f2937;
            padding: 3rem 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            width: 100%;
            max-width: 28rem;
        }
        
        .candle-glow {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at center, rgba(90, 122, 81, 0.1) 0%, rgba(31, 41, 55, 0) 70%);
            pointer-events: none;
        }
        
        .loading-spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 2rem;
            height: 2rem;
            border: 3px solid rgba(90, 122, 81, 0.1);
            border-top-color: var(--candle-glow);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            to {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }
        
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
</body>
</html>
