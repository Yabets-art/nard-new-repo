<svg width="50" height="50" viewBox="0 0 512 512" fill="none" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
    <!-- Candle Base -->
    <rect x="176" y="280" width="160" height="200" rx="15" fill="#f8f0e3" />
    
    <!-- Candle Glow Effect -->
    <circle cx="256" cy="280" r="100" fill="url(#candle_glow)" />
    
    <!-- Wick -->
    <rect x="254" y="120" width="4" height="50" rx="2" fill="#4a3f35" />
    
    <!-- Flame -->
    <path d="M256 50C256 50 220 120 235 150C250 180 262 180 256 210C256 210 290 170 275 140C260 110 256 80 256 50Z" fill="url(#flame_gradient)" />
    
    <!-- Melted Wax -->
    <path d="M176 280C176 280 186 260 256 260C326 260 336 280 336 280" fill="#f8f0e3" />
    
    <!-- Drips -->
    <path d="M190 280C190 280 195 320 185 330C175 340 190 350 190 350" stroke="#f8f0e3" stroke-width="8" stroke-linecap="round" />
    <path d="M322 280C322 280 317 330 327 340C337 350 322 360 322 360" stroke="#f8f0e3" stroke-width="8" stroke-linecap="round" />
    
    <!-- Gradients -->
    <defs>
        <radialGradient id="candle_glow" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(256 280) rotate(-90) scale(100)">
            <stop offset="0" stop-color="#ffc17d" stop-opacity="0.5" />
            <stop offset="1" stop-color="#ffc17d" stop-opacity="0" />
        </radialGradient>
        <linearGradient id="flame_gradient" x1="256" y1="50" x2="256" y2="210" gradientUnits="userSpaceOnUse">
            <stop offset="0" stop-color="#ff5722" />
            <stop offset="1" stop-color="#ff9c35" />
        </linearGradient>
    </defs>
</svg>
