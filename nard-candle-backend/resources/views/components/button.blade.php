<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-candle-flame border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-candle-glow focus:outline-none focus:border-candle-glow focus:ring ring-candle-glow ring-opacity-50 active:bg-candle-flame disabled:opacity-25 transition ease-in-out duration-150 btn-candle']) }}>
    {{ $slot }}
</button>
