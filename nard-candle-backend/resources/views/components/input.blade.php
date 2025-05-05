@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'rounded-md shadow-sm border-gray-300 focus:border-candle-flame focus:ring focus:ring-candle-glow focus:ring-opacity-50']) !!}>
