<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="theme-color" content="#3b82f6">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/apple-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/favicon.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <title>@yield('title', 'FuelFlow') - {{ config('app.name') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- PWA Support -->
    <link rel="stylesheet" href="{{ asset('css/mobile.css') }}">

    @stack('styles')
</head>

<body class="bg-gray-50 font-sans">
    <!-- Mobile Header -->
    <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
        <div class="px-4 py-3">
            <div class="flex items-center justify-between">
                <!-- Menu Button -->
                <button id="mobile-menu-button"
                    class="p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100">
                    <i class="fas fa-bars text-lg"></i>
                </button>

                <!-- Logo -->
                <div class="flex-1 text-center">
                    <h1 class="text-lg font-semibold text-gray-900">@yield('page-title', 'FuelFlow')</h1>
                </div>

                <!-- Notifications -->
                <div class="relative">
                    <a href="{{ route('notifications.index') }}"
                        class="p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 relative">
                        <i class="fas fa-bell text-lg"></i>
                        @if($unreadNotifications > 0)
                            <span
                                class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                {{ $unreadNotifications }}
                            </span>
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Sidebar -->
    <div id="mobile-sidebar"
        class="fixed inset-0 z-50 transform -translate-x-full transition-transform duration-300 ease-in-out lg:hidden">
        <div class="fixed inset-0 bg-black bg-opacity-50" id="mobile-sidebar-overlay"></div>
        <div class="relative flex-1 flex flex-col max-w-xs w-full bg-white">
            <div class="absolute top-0 right-0 -mr-12 pt-2">
                <button id="mobile-sidebar-close"
                    class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                    <i class="fas fa-times text-white"></i>
                </button>
            </div>
            <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                <div class="flex-shrink-0 flex items-center px-4">
                    <img class="h-8 w-auto" src="{{ asset('img/logo-ct.png') }}" alt="Logo">
                    <span class="ml-2 text-lg font-semibold text-gray-900">FuelFlow</span>
                </div>
                <nav class="mt-5 px-2 space-y-1">
                    <a href="{{ route('dashboard') }}"
                        class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-blue-100 text-blue-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-home mr-3"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('fuel-requests.index') }}"
                        class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('fuel-requests*') ? 'bg-blue-100 text-blue-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-gas-pump mr-3"></i>
                        Fuel Requests
                    </a>
                    <a href="{{ route('clients.index') }}"
                        class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('clients*') ? 'bg-blue-100 text-blue-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-users mr-3"></i>
                        Clients
                    </a>
                    <a href="{{ route('stations.index') }}"
                        class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('stations*') ? 'bg-blue-100 text-blue-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-building mr-3"></i>
                        Stations
                    </a>
                    <a href="{{ route('vehicles.index') }}"
                        class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('vehicles*') ? 'bg-blue-100 text-blue-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-car mr-3"></i>
                        Vehicles
                    </a>
                    <a href="{{ route('receipts.index') }}"
                        class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('receipts*') ? 'bg-blue-100 text-blue-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-receipt mr-3"></i>
                        Receipts
                    </a>
                    <a href="{{ route('payments.index') }}"
                        class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('payments*') ? 'bg-blue-100 text-blue-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-credit-card mr-3"></i>
                        Payments
                    </a>
                    <a href="{{ route('reports.index') }}"
                        class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('reports*') ? 'bg-blue-100 text-blue-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-chart-bar mr-3"></i>
                        Reports
                    </a>
                </nav>
            </div>
            <div class="flex-shrink-0 flex border-t border-gray-200 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center">
                            <span class="text-sm font-medium text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                    </div>
                    <div class="ml-3">
                        <p class="text-base font-medium text-gray-700">{{ auth()->user()->name }}</p>
                        <p class="text-sm font-medium text-gray-500">{{ ucfirst(auth()->user()->role) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="flex-1 relative pb-20">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4 mx-4 mt-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mx-4 mt-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('warning'))
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mx-4 mt-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">{{ session('warning') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('info'))
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mx-4 mt-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">{{ session('info') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Page Content -->
        <div class="px-4 py-6">
            @yield('content')
        </div>
    </main>

    <!-- Bottom Navigation (Mobile) -->
    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 lg:hidden">
        <div class="flex justify-around">
            <a href="{{ route('dashboard') }}"
                class="flex flex-col items-center py-2 px-3 {{ request()->routeIs('dashboard') ? 'text-blue-600' : 'text-gray-500' }}">
                <i class="fas fa-home text-lg mb-1"></i>
                <span class="text-xs">Home</span>
            </a>
            <a href="{{ route('fuel-requests.index') }}"
                class="flex flex-col items-center py-2 px-3 {{ request()->routeIs('fuel-requests*') ? 'text-blue-600' : 'text-gray-500' }}">
                <i class="fas fa-gas-pump text-lg mb-1"></i>
                <span class="text-xs">Requests</span>
            </a>
            <a href="{{ route('notifications.index') }}"
                class="flex flex-col items-center py-2 px-3 {{ request()->routeIs('notifications*') ? 'text-blue-600' : 'text-gray-500' }} relative">
                <i class="fas fa-bell text-lg mb-1"></i>
                <span class="text-xs">Alerts</span>
                @if($unreadNotifications > 0)
                    <span
                        class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">
                        {{ $unreadNotifications }}
                    </span>
                @endif
            </a>
            <a href="{{ route('reports.index') }}"
                class="flex flex-col items-center py-2 px-3 {{ request()->routeIs('reports*') ? 'text-blue-600' : 'text-gray-500' }}">
                <i class="fas fa-chart-bar text-lg mb-1"></i>
                <span class="text-xs">Reports</span>
            </a>
            <a href="{{ route('profile.index') }}"
                class="flex flex-col items-center py-2 px-3 {{ request()->routeIs('profile*') ? 'text-blue-600' : 'text-gray-500' }}">
                <i class="fas fa-user text-lg mb-1"></i>
                <span class="text-xs">Profile</span>
            </a>
        </div>
    </nav>

    <!-- Scripts -->
    <script>
        // Mobile sidebar toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileSidebar = document.getElementById('mobile-sidebar');
        const mobileSidebarClose = document.getElementById('mobile-sidebar-close');
        const mobileSidebarOverlay = document.getElementById('mobile-sidebar-overlay');

        mobileMenuButton.addEventListener('click', () => {
            mobileSidebar.classList.remove('-translate-x-full');
        });

        mobileSidebarClose.addEventListener('click', () => {
            mobileSidebar.classList.add('-translate-x-full');
        });

        mobileSidebarOverlay.addEventListener('click', () => {
            mobileSidebar.classList.add('-translate-x-full');
        });

        // Close sidebar when clicking on links
        document.querySelectorAll('#mobile-sidebar a').forEach(link => {
            link.addEventListener('click', () => {
                mobileSidebar.classList.add('-translate-x-full');
            });
        });

        // PWA Install prompt
        let deferredPrompt;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            // Show install button or banner
        });

        // Service Worker registration
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then((registration) => {
                        console.log('SW registered: ', registration);
                    })
                    .catch((registrationError) => {
                        console.log('SW registration failed: ', registrationError);
                    });
            });
        }
    </script>

    @stack('scripts')
</body>

</html>