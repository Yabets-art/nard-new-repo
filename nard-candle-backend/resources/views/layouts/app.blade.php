<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Nard Candles') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <link href="{{ asset('/NardAdmin/css/sb-admin-2.css') }}" rel="stylesheet">
    <link href="/NardAdmin/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('/NardAdmin/js/sb-admin-2.js') }}"></script>
    <script src="{{ asset('/NardAdmin/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('/NardAdmin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('/NardAdmin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('/NardAdmin/vendor/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('/NardAdmin/js/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('/NardAdmin/js/demo/chart-pie-demo.js') }}"></script>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>

        @include('admin.partials.sidebar')

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>
</body>

</html>
