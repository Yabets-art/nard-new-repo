@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-green-600 bg-green-50 p-3 rounded-md border border-green-200 animate__animated animate__fadeIn']) }}>
        {{ $status }}
    </div>
@endif
