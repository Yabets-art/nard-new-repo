@props(['errors'])

@if ($errors->any())
    <div {{ $attributes->merge(['class' => 'rounded-md p-4 bg-red-50 border border-red-300 animate__animated animate__headShake']) }}>
        <div class="font-medium text-red-600">
            {{ __('Whoops! Something went wrong.') }}
        </div>

        <ul class="mt-3 list-disc list-inside text-sm text-red-600">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
