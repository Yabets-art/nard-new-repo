<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'Nard Candles Admin')</title>
    <link href="{{ asset('/NardAdmin/css/sb-admin-2.css') }}" rel="stylesheet">
    <link href="/NardAdmin/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="btn btn-primary">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('/NardAdmin/js/sb-admin-2.js') }}"></script>
    <script src="{{ asset('/NardAdmin/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('/NardAdmin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('/NardAdmin/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('/NardAdmin/vendor/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('/NardAdmin/js/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('/NardAdmin/js/demo/chart-pie-demo.js') }}"></script>
</body>

</html>
