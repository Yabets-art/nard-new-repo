<!-- resources/views/admin/partials/sidebar.blade.php -->
<aside>
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('assets/logo.png') }}" alt="Nard Candles Logo" style="width: 50px; height: 50px;">
        </div>
        <div class="sidebar-brand-text mx-3">Nard Candles Admin</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('admin.index') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>
    
    <!-- Divider -->
    <hr class="sidebar-divider">
    
    <!-- Nav Item - Product -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.product') }}">
            <i class="fas fa-fw fa-box"></i>
            <span>Product Management</span>
        </a>
    </li>
    
    <!-- Nav Item - Order -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOrder" aria-expanded="true" aria-controls="collapseOrder">
            <i class="fas fa-fw fa-shopping-cart"></i>
            <span>Order Management</span>
        </a>
        <div id="collapseOrder" class="collapse" aria-labelledby="headingOrder" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Order Options:</h6>
                {{-- <a class="collapse-item" href="{{ route('admin.order') }}">Checkin</a> --}}
                <a class="collapse-item" href="#">Checkin</a>
                <a class="collapse-item" href="{{ route('custom-orders.index') }}">Custom Order</a>
            </div>
        </div>
    </li>
    
    <!-- Nav Item - Home -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseHome" aria-expanded="true" aria-controls="collapseHome">
            <i class="fas fa-fw fa-home"></i>
            <span>Home Configurations</span>
        </a>
        <div id="collapseHome" class="collapse" aria-labelledby="headingHome" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Home Configurations:</h6>
                <a class="collapse-item" href="{{ route('admin.promotions.index') }}">Promotion Banner</a>
                <a class="collapse-item" href="{{ route('admin.featured-products.index') }}">Featured</a>
                <a class="collapse-item" href="{{ route('admin.youtube-videos.index') }}">YouTube</a>
            </div>
        </div>
    </li>
    
    <!-- Nav Item - Post -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.post') }}">
            <i class="fas fa-fw fa-edit"></i>
            <span>Post Management</span>
        </a>
    </li>
    
    <!-- Nav Item - Message -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('admin.message.index') }}">
            <i class="fas fa-fw fa-envelope"></i>
            <span>Messages</span>
        </a>
    </li>
</aside>