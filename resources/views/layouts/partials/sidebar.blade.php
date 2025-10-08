<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2 bg-white my-2"
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand px-4 py-3 m-0" href="{{ route('dashboard') }}">
            <i class="fas fa-gas-pump text-primary" style="font-size: 1.5rem;"></i>
            <span class="ms-2 font-weight-bold text-dark" style="font-size: 1.1rem;">FuelFlow</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0 mb-2">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                    href="{{ route('dashboard') }}">
                    <i class="material-symbols-rounded opacity-5">dashboard</i>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>

            @if(auth()->user()->isSuperAdmin())
                <!-- Super Admin Navigation -->
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">
                        Platform Management
                    </h6>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('super-admin.businesses.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                        href="{{ route('super-admin.businesses.index') }}">
                        <i class="material-symbols-rounded opacity-5">business</i>
                        <span class="nav-link-text ms-1">Businesses</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('super-admin.users.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                        href="{{ route('super-admin.users.index') }}">
                        <i class="material-symbols-rounded opacity-5">people</i>
                        <span class="nav-link-text ms-1">System Users</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('super-admin.reports.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                        href="{{ route('super-admin.reports.index') }}">
                        <i class="material-symbols-rounded opacity-5">analytics</i>
                        <span class="nav-link-text ms-1">Platform Reports</span>
                    </a>
                </li>

                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">
                        System
                    </h6>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('profile.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                        href="{{ route('profile.index') }}">
                        <i class="material-symbols-rounded opacity-5">person</i>
                        <span class="nav-link-text ms-1">Profile</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-dark" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="material-symbols-rounded opacity-5">logout</i>
                        <span class="nav-link-text ms-1">Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            @else

            @if(auth()->user()->isAdmin() || auth()->user()->isStationManager())
                <!-- Admin & Station Manager Navigation -->
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">
                        {{ auth()->user()->isAdmin() ? 'System Management' : 'Station Management' }}
                    </h6>
                </li>

                @if(auth()->user()->isAdmin())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('stations.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                            href="{{ route('stations.index') }}">
                            <i class="material-symbols-rounded opacity-5">local_gas_station</i>
                            <span class="nav-link-text ms-1">Stations</span>
                        </a>
                    </li>
                @endif

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('users.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                        href="{{ route('users.index') }}">
                        <i class="material-symbols-rounded opacity-5">people</i>
                        <span class="nav-link-text ms-1">{{ auth()->user()->isStationManager() ? 'Staff' : 'Users' }}</span>
                    </a>
                </li>

                {{-- Commented out for demo - route doesn't exist yet
                @if(auth()->user()->isAdmin())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('station-managers.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                            href="{{ route('station-managers.index') }}">
                            <i class="material-symbols-rounded opacity-5">supervisor_account</i>
                            <span class="nav-link-text ms-1">Station Managers</span>
                        </a>
                    </li>
                @endif
                --}}

                {{-- Commented out for demo - routes may not exist yet --}}
            @endif

            @if(auth()->user()->isAdmin() || auth()->user()->isStationManager())
                <!-- Client Management -->
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">Client Management
                    </h6>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('clients.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                        href="{{ route('clients.index') }}">
                        <i class="material-symbols-rounded opacity-5">business</i>
                        <span class="nav-link-text ms-1">Clients</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('vehicles.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                        href="{{ route('vehicles.index') }}">
                        <i class="material-symbols-rounded opacity-5">directions_car</i>
                        <span class="nav-link-text ms-1">Fleet Vehicles</span>
                    </a>
                </li>
            @endif

            <!-- Fuel Request Management -->
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">Fuel Requests</h6>
            </li>

            <li class="nav-item">
                @php($clientListRoute = auth()->user()->isClient() ? 'client-portal.requests.index' : 'fuel-requests.index')
                <a class="nav-link {{ request()->routeIs('fuel-requests.*') || request()->routeIs('client-portal.requests.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                    href="{{ route($clientListRoute) }}">
                    <i class="material-symbols-rounded opacity-5">assignment</i>
                    <span class="nav-link-text ms-1">All Requests</span>
                </a>
            </li>

            @if(auth()->user()->isStationManager() && \Illuminate\Support\Facades\Route::has('fuel-requests.pending'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('fuel-requests.pending') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                        href="{{ route('fuel-requests.pending') }}">
                        <i class="material-symbols-rounded opacity-5">pending_actions</i>
                        <span class="nav-link-text ms-1">Pending Approval</span>
                    </a>
                </li>
            @endif

            @if(auth()->user()->isFuelPumper() && \Illuminate\Support\Facades\Route::has('fuel-requests.my-assignments'))
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('fuel-requests.my-assignments') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                        href="{{ route('fuel-requests.my-assignments') }}">
                        <i class="material-symbols-rounded opacity-5">assignment_ind</i>
                        <span class="nav-link-text ms-1">My Assignments</span>
                    </a>
                </li>
            @endif

            @if(auth()->user()->isClient())
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('fuel-requests.create') || request()->routeIs('client-portal.requests.create') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                        href="{{ route('client-portal.requests.create') }}">
                        <i class="material-symbols-rounded opacity-5">add</i>
                        <span class="nav-link-text ms-1">New Request</span>
                    </a>
                </li>
            @endif

            <!-- Station Operations -->
            @if(auth()->user()->isStationManager() || auth()->user()->isFuelPumper())
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">Station Operations
                </h6>
            </li>

            <li class="nav-item">
                @php($hasInventoryRoute = \Illuminate\Support\Facades\Route::has('stations.inventory'))
                <a class="nav-link {{ $hasInventoryRoute && request()->routeIs('stations.inventory') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                    href="{{ $hasInventoryRoute ? route('stations.inventory', auth()->user()->station_id) : '#' }}">
                    <i class="material-symbols-rounded opacity-5">inventory</i>
                    <span class="nav-link-text ms-1">Fuel Inventory</span>
                </a>
            </li>

            @endif

            <!-- Receipts Management -->
            @if(auth()->user()->isClient() || auth()->user()->isStationManager() || auth()->user()->isFuelPumper() || auth()->user()->isTreasury() || auth()->user()->isAdmin())
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">Receipts Management
                    </h6>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('receipts.index') || request()->routeIs('receipts.create') || request()->routeIs('receipts.show') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                        href="{{ route('receipts.index') }}">
                        <i class="material-symbols-rounded opacity-5">receipt_long</i>
                        <span class="nav-link-text ms-1">Receipts</span>
                    </a>
                </li>
            @endif

            <!-- Financial Management -->
            @if(auth()->user()->isTreasury() || auth()->user()->isAdmin() || auth()->user()->isClient())
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">Financial Management
                </h6>
            </li>

            @if(auth()->user()->isTreasury())
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('receipts.pending') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                        href="{{ route('receipts.pending') }}">
                        <i class="material-symbols-rounded opacity-5">pending_actions</i>
                        <span class="nav-link-text ms-1">Pending Receipts</span>
                    </a>
                </li>
            @endif

            @if(!auth()->user()->isClient())
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('clients.overdue') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                        href="{{ route('clients.overdue') }}">
                        <i class="material-symbols-rounded opacity-5">warning</i>
                        <span class="nav-link-text ms-1">Overdue Accounts</span>
                    </a>
                </li>
            @endif

            <li class="nav-item">
                @php($paymentsIndexRoute = auth()->user()->isClient() ? 'client-portal.payments.index' : 'payments.index')
                <a class="nav-link {{ request()->routeIs('payments.*') || request()->routeIs('client-portal.payments.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                    href="{{ route($paymentsIndexRoute) }}">
                    <i class="material-symbols-rounded opacity-5">payments</i>
                    <span class="nav-link-text ms-1">Payments</span>
                </a>
            </li>
            @endif

            <!-- Reports & Analytics -->
            <li class="nav-item mt-3">
                <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">Reports & Analytics
                </h6>
            </li>

            @if(auth()->user()->isAdmin())
                <!-- Admin: Full system reports -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reports.index') || request()->routeIs('reports.system') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                        href="{{ route('reports.index') }}">
                        <i class="material-symbols-rounded opacity-5">analytics</i>
                        <span class="nav-link-text ms-1">System Reports</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reports.index') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                        href="{{ route('reports.index') }}">
                        <i class="fas fa-chart-bar opacity-5"></i>
                        <span class="nav-link-text ms-1">All Reports</span>
                    </a>
                </li>
            @endif

            @if(auth()->user()->isStationManager())
                <!-- Station Manager: Only station reports -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reports.station') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                        href="{{ route('reports.station') }}">
                        <i class="material-symbols-rounded opacity-5">local_gas_station</i>
                        <span class="nav-link-text ms-1">Station Reports</span>
                    </a>
                </li>
            @endif

            @if(auth()->user()->isFuelPumper())
                <!-- Fuel Pumper: Only personal activity -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reports.my-activity') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                        href="{{ route('reports.my-activity') }}">
                        <i class="material-symbols-rounded opacity-5">person</i>
                        <span class="nav-link-text ms-1">My Activity</span>
                    </a>
                </li>
            @endif

            @if(auth()->user()->isTreasury())
                <!-- Treasury: Financial reports only -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reports.financial') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                        href="{{ route('reports.financial') }}">
                        <i class="material-symbols-rounded opacity-5">account_balance</i>
                        <span class="nav-link-text ms-1">Financial Reports</span>
                    </a>
                </li>
            @endif

            @if(auth()->user()->isClient())
                <!-- Client: Dedicated client reports -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('client-portal.reports.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                        href="{{ route('client-portal.reports.index') }}">
                        <i class="material-symbols-rounded opacity-5">analytics</i>
                        <span class="nav-link-text ms-1">My Reports</span>
                    </a>
                </li>
            @endif

                <!-- Notifications -->
                <li class="nav-item">
                    @php($notificationsRoute = auth()->user()->isClient() ? 'client-portal.notifications.index' : 'notifications.index')
                    <a class="nav-link {{ request()->routeIs('notifications.*') || request()->routeIs('client-portal.notifications.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                        href="{{ route($notificationsRoute) }}">
                        <i class="material-symbols-rounded opacity-5">notifications</i>
                        <span class="nav-link-text ms-1">Notifications</span>
                    </a>
                </li>

                <!-- System -->
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">System</h6>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('onboarding.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                        href="{{ route('onboarding.index') }}">
                        <i class="material-symbols-rounded opacity-5">school</i>
                        <span class="nav-link-text ms-1">Onboarding Guide</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('profile.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                        href="{{ route('profile.index') }}">
                        <i class="material-symbols-rounded opacity-5">person</i>
                        <span class="nav-link-text ms-1">Profile</span>
                    </a>
                </li>

                @if(auth()->user()->isAdmin())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('settings.*') ? 'active bg-gradient-dark text-white' : 'text-dark' }}"
                            href="{{ route('settings.index') }}">
                            <i class="material-symbols-rounded opacity-5">settings</i>
                            <span class="nav-link-text ms-1">System Settings</span>
                        </a>
                    </li>
                @endif

                <li class="nav-item">
                    <a class="nav-link text-dark" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="material-symbols-rounded opacity-5">logout</i>
                        <span class="nav-link-text ms-1">Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            @endif
        </ul>
    </div>
</aside>