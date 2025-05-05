@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-candle-shadow']) }}>
    {{ $value ?? $slot }}
</label>
