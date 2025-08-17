<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2 bg-white my-2" id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand px-4 py-3 m-0" href="{{ route('dashboard') }}">
            <img src="{{ asset('assets/img/logo-ct-dark.png') }}" class="navbar-brand-img" width="26" height="26" alt="FuelFlow Logo">
            <span class="ms-1 text-sm text-dark">FuelFlow</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0 mb-2">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('dashboard') }}">
                    <i class="material-symbols-rounded opacity-5">dashboard</i>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">Billing Management</h6>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('billing.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('billing.index') }}">
                    <i class="material-symbols-rounded opacity-5">receipt_long</i>
                    <span class="nav-link-text ms-1">Billing</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('customers.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('customers.index') }}">
                    <i class="material-symbols-rounded opacity-5">people</i>
                    <span class="nav-link-text ms-1">Customers</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('invoices.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('invoices.index') }}">
                    <i class="material-symbols-rounded opacity-5">description</i>
                    <span class="nav-link-text ms-1">Invoices</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('payments.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('payments.index') }}">
                    <i class="material-symbols-rounded opacity-5">payments</i>
                    <span class="nav-link-text ms-1">Payments</span>
                </a>
            </li>
            
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">Fuel Management</h6>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('fuel.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('fuel.index') }}">
                    <i class="material-symbols-rounded opacity-5">local_gas_station</i>
                    <span class="nav-link-text ms-1">Fuel Inventory</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('deliveries.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('deliveries.index') }}">
                    <i class="material-symbols-rounded opacity-5">local_shipping</i>
                    <span class="nav-link-text ms-1">Deliveries</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('reports.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('reports.index') }}">
                    <i class="material-symbols-rounded opacity-5">analytics</i>
                    <span class="nav-link-text ms-1">Reports</span>
                </a>
            </li>
            
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">System</h6>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('profile.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('profile.index') }}">
                    <i class="material-symbols-rounded opacity-5">person</i>
                    <span class="nav-link-text ms-1">Profile</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('settings.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}" href="{{ route('settings.index') }}">
                    <i class="material-symbols-rounded opacity-5">settings</i>
                    <span class="nav-link-text ms-1">Settings</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link text-dark" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="material-symbols-rounded opacity-5">logout</i>
                    <span class="nav-link-text ms-1">Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
</aside>
