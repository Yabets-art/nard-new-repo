<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'Nard Candles Admin')</title>
    <link href="{{ asset('/NardAdmin/css/sb-admin-2.css') }}" rel="stylesheet">
    <link href="/NardAdmin/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
</head>
<body>
    @include('admin.partials.topbar')

    <div style="display: flex; flex-direction: row; height: 100vh;">
        <!-- Sidebar -->
        <div style="width: 220px; margin-right: 30px; margin-top: -90px;  min-height: 100vh; background-color: #2c3e50;">
            @include('admin.partials.sidebar')
        </div>
        
        <!-- Main Content -->
        <main style="flex-grow: 1; padding: 0px; background-color: #f8f9fa;" class="position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
            @yield('content')
        </main>
    </div>

    @include('admin.partials.footer')

    <script src="{{ asset('/NardAdmin/js/sb-admin-2.js') }}"></script>
    <script src="{{ asset('/NardAdmin/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('/NardAdmin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('/NardAdmin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('/NardAdmin/vendor/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('/NardAdmin/js/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('/NardAdmin/js/demo/chart-pie-demo.js') }}"></script>
</body>

</html>
