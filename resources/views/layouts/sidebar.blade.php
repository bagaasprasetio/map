<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-text mx-3">REBOOT APP</div>
    </a>

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->routeIs(auth()->user()->role.'.index') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route(auth()->user()->role.'.index') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    @if (auth()->user()->role === 'ap')

    <li class="nav-item {{ request()->routeIs('transaksi.master') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('transaksi.master') }}">
            <i class="fas fa-fw fa-clock"></i>
            <span>Transaksi Pangkalan</span></a>
    </li>
    
    @endif

    @if (auth()->user()->role === 'sa' || auth()->user()->role === 'ao')

    <li class="nav-item {{ request()->routeIs('pangkalan.master') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('pangkalan.master') }}">
            <i class="fas fa-fw fa-building"></i>
            <span>Kelola Pangkalan</span></a>
    </li>

    <li class="nav-item {{ request()->routeIs('user.master') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('user.master') }}">
            <i class="fas fa-fw fa-user-alt"></i>
            <span>Kelola Pengguna</span></a>
    </li>

    <li class="nav-item {{ request()->routeIs('subs.master') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('subs.master') }}">
            <i class="fas fa-fw fa-rotate"></i>
            <span>Kelola Langganan Admin Pangkalan</span></a>
    </li>

    @endif

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
