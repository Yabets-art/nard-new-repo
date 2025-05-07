<aside class="sidebar">
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion vh-100" id="accordionSidebar">
        
        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center py-3" href="{{ route('dashboard') }}">
            <div class="sidebar-brand-icon">
                <img src="{{ asset('assets/logo.png') }}" alt="Nard Candles Logo" class="brand-logo">
            </div>
            <div class="sidebar-brand-text mx-3">Nard Candles</div>
        </a>

        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <hr class="sidebar-divider">

        <!-- Nav Item - Product -->
        <li class="nav-item {{ request()->routeIs('admin.product') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.product') }}">
                <i class="fas fa-fw fa-box"></i>
                <span>Product Management</span>
            </a>
        </li>

        <!-- Nav Item - Order -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOrder" aria-expanded="false">
                <i class="fas fa-fw fa-shopping-cart"></i>
                <span>Order Management</span>
            </a>
            <div id="collapseOrder" class="collapse" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Order Options:</h6>
                    <a class="collapse-item" href="#">Checkin</a>
                    <a class="collapse-item" href="{{ route('custom-orders.index') }}">Custom Order</a>
                </div>
            </div>
        </li>

        <!-- Nav Item - Home -->
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseHome" aria-expanded="false">
                <i class="fas fa-fw fa-home"></i>
                <span>Home Configurations</span>
            </a>
            <div id="collapseHome" class="collapse" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Home Configurations:</h6>
                    <a class="collapse-item" href="{{ route('admin.promotions.index') }}">Promotion Banner</a>
                    <a class="collapse-item" href="{{ route('admin.featured-products.index') }}">Featured</a>
                    <a class="collapse-item" href="{{ route('admin.youtube-videos.index') }}">YouTube</a>
                </div>
            </div>
        </li>

        <!-- Nav Item - Post -->
        <li class="nav-item {{ request()->routeIs('admin.post') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.post') }}">
                <i class="fas fa-fw fa-edit"></i>
                <span>Post Management</span>
            </a>
        </li>

        <!-- Nav Item - Message -->
        <li class="nav-item {{ request()->routeIs('admin.message.index') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.message.index') }}">
                <i class="fas fa-fw fa-envelope"></i>
                <span>Messages</span>
            </a>
        </li>

    </ul>
</aside>

<!-- Custom CSS -->
<style>
    .sidebar {
        width: 200px;
        height: 110vh;
        /* position: fixed;  */
        top: 0;
        left: 0;
        
        background: linear-gradient(180deg, #4e73df, #224abe);
        /* overflow-y: auto; */
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.2);
    }

    .sidebar .nav-item {
        padding: 10px;
    }

    .sidebar .nav-link {
        color: #fff;
        transition: 0.3s ease;
    }

    .sidebar .nav-link:hover,
    .sidebar .nav-item.active .nav-link {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 5px;
    }

    .sidebar .collapse-inner a {
        padding: 8px 20px;
        display: block;
        color: #4e73df;
        transition: 0.3s;
    }

    .sidebar .collapse-inner a:hover {
        color: #224abe;
        background: rgba(0, 0, 0, 0.05);
    }

    .sidebar-brand-icon img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
    }

    .sidebar-divider {
        border-top: 1px solid rgba(255, 255, 255, 0.2);
        margin: 10px 0;
    }
</style>
