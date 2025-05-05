<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Nard Candles') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
        
        <!-- Animation Libraries -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
        
        <!-- Scripts -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <script src="{{ asset('js/app.js') }}" defer></script>
        
        <style>
            :root {
                --candle-light: #fcf5e5;
                --candle-glow: #ffc17d;
                --candle-flame: #ff9c35;
                --candle-wax: #f8f0e3;
                --candle-shadow: #4a3f35;
            }
            
            body {
                background: linear-gradient(135deg, var(--candle-shadow) 0%, #2d2621 100%);
                background-attachment: fixed;
                font-family: 'Nunito', sans-serif;
                position: relative;
                overflow-x: hidden;
            }
            
            body::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: radial-gradient(circle at center, transparent 0%, rgba(0,0,0,0.7) 100%);
                pointer-events: none;
                z-index: 0;
            }
            
            .font-sans {
                font-family: 'Nunito', sans-serif;
            }
            
            .candle-card {
                background-color: var(--candle-wax);
                border-radius: 10px;
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
                overflow: hidden;
                position: relative;
            }
            
            .candle-card::before {
                content: '';
                position: absolute;
                top: -10px;
                left: 50%;
                transform: translateX(-50%);
                width: 20px;
                height: 20px;
                background: var(--candle-flame);
                border-radius: 50% 50% 0 50%;
                transform-origin: bottom center;
                filter: blur(5px);
                animation: flicker 3s infinite alternate;
                z-index: 10;
            }
            
            @keyframes flicker {
                0%, 100% { opacity: 1; transform: translateX(-50%) scale(1) rotate(-45deg); }
                25% { opacity: 0.8; transform: translateX(-51%) scale(1.05) rotate(-40deg); }
                50% { opacity: 0.9; transform: translateX(-49%) scale(0.95) rotate(-50deg); }
                75% { opacity: 0.8; transform: translateX(-50%) scale(1.1) rotate(-45deg); }
            }
            
            .candle-glow {
                position: absolute;
                top: -100px;
                left: 50%;
                transform: translateX(-50%);
                width: 200px;
                height: 200px;
                background: radial-gradient(circle at center, var(--candle-glow) 0%, transparent 70%);
                opacity: 0.6;
                z-index: 5;
                animation: glow 4s infinite alternate;
            }
            
            @keyframes glow {
                0%, 100% { opacity: 0.6; transform: translateX(-50%) scale(1); }
                50% { opacity: 0.8; transform: translateX(-50%) scale(1.1); }
            }
            
            .loading-spinner {
                display: none;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 50px;
                height: 50px;
            }
            
            .loading-spinner:after {
                content: "";
                display: block;
                width: 30px;
                height: 30px;
                border-radius: 50%;
                border: 5px solid var(--candle-glow);
                border-color: var(--candle-flame) transparent var(--candle-flame) transparent;
                animation: spinner 1.2s linear infinite;
            }
            
            @keyframes spinner {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            
            .form-active .loading-spinner {
                display: block;
            }
            
            .form-active form {
                opacity: 0.5;
                pointer-events: none;
            }
            
            .btn-candle {
                background: linear-gradient(to right, var(--candle-flame), var(--candle-glow));
                border: none;
                color: var(--candle-shadow);
                font-weight: bold;
                transition: all 0.3s ease;
                position: relative;
                overflow: hidden;
            }
            
            .btn-candle:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(255, 156, 53, 0.4);
            }
            
            .btn-candle:active {
                transform: translateY(0);
            }
            
            .btn-candle::after {
                content: '';
                position: absolute;
                top: 50%;
                left: 50%;
                width: 5px;
                height: 5px;
                background: rgba(255, 255, 255, 0.5);
                opacity: 0;
                border-radius: 100%;
                transform: scale(1, 1) translate(-50%);
                transform-origin: 50% 50%;
            }
            
            .btn-candle:hover::after {
                animation: ripple 1s ease-out;
            }
            
            @keyframes ripple {
                0% { transform: scale(0, 0); opacity: 0.5; }
                100% { transform: scale(20, 20); opacity: 0; }
            }
            
            .input-candle {
                background-color: rgba(255, 255, 255, 0.8);
                border: 1px solid var(--candle-glow);
                transition: all 0.3s ease;
            }
            
            .input-candle:focus {
                background-color: white;
                border-color: var(--candle-flame);
                box-shadow: 0 0 0 3px rgba(255, 156, 53, 0.25);
            }
            
            .label-candle {
                color: var(--candle-shadow);
                font-weight: 600;
            }
            
            .auth-page-title {
                font-family: 'Playfair Display', serif;
                color: var(--candle-glow);
                text-shadow: 0 2px 4px rgba(0,0,0,0.3);
                font-weight: 700;
            }
            
            .floating-particles {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                pointer-events: none;
                z-index: 0;
            }
            
            .particle {
                position: absolute;
                width: 6px;
                height: 6px;
                background: var(--candle-glow);
                border-radius: 50%;
                opacity: 0;
                animation: float-up 8s infinite ease-out;
            }
            
            @keyframes float-up {
                0% { 
                    transform: translateY(100vh) translateX(0); 
                    opacity: 0; 
                }
                10% { 
                    opacity: 0.1; 
                }
                90% { 
                    opacity: 0.1; 
                }
                100% { 
                    transform: translateY(0) translateX(var(--x-end)); 
                    opacity: 0; 
                }
            }
        </style>
    </head>
    <body>
        <div class="floating-particles" id="particles"></div>
        <div class="font-sans text-gray-900 antialiased">
            {{ $slot }}
        </div>
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Create floating particles
                const particlesContainer = document.getElementById('particles');
                for (let i = 0; i < 20; i++) {
                    createParticle(particlesContainer, i);
                }
                
                // Form submission animation
                const forms = document.querySelectorAll('form');
                forms.forEach(form => {
                    form.addEventListener('submit', function() {
                        form.closest('.candle-card-wrapper').classList.add('form-active');
                    });
                });
            });
            
            function createParticle(container, index) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                
                // Random starting position along bottom
                const xStart = Math.random() * 100;
                // Random ending x position for drift
                const xEnd = (Math.random() - 0.5) * 100;
                
                particle.style.left = `${xStart}%`;
                particle.style.bottom = '0';
                particle.style.setProperty('--x-end', `${xEnd}px`);
                
                // Random size
                const size = Math.random() * 4 + 2;
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                
                // Random opacity
                particle.style.opacity = Math.random() * 0.3;
                
                // Random delay
                const delay = Math.random() * 5;
                particle.style.animationDelay = `${delay}s`;
                
                // Random duration
                const duration = Math.random() * 10 + 8;
                particle.style.animationDuration = `${duration}s`;
                
                container.appendChild(particle);
                
                // Remove and recreate particle when animation ends
                setTimeout(() => {
                    particle.remove();
                    createParticle(container, index);
                }, (delay + duration) * 1000);
            }
        </script>
    </body>
</html>
