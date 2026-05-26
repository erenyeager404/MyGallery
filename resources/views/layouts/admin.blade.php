<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin — OurMemora</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-950 text-white min-h-screen flex">

    <div class="fixed inset-0 z-0 bg-gray-950"></div>

    <aside class="sidebar sidebar-admin" id="sidebar">
        <div class="sidebar-logo-area">
            <a href="{{ route('admin.dashboard') }}" class="relative w-full flex items-center justify-center">
                <span class="sidebar-logo-short text-red-400">M</span>
                <span class="sidebar-logo-full">
                    Our<span class="text-red-400">Memora</span>
                </span>
            </a>
        </div>

        <nav class="flex-1 py-4 space-y-1">
            @php
                $adminNav = [
                    ['route' => 'admin.dashboard', 'icon' => '⊞', 'label' => 'Dashboard'],
                    ['route' => 'admin.engagement', 'icon' => '⎇', 'label' => 'Engagement'],
                    ['route' => 'profile', 'icon' => '◉', 'label' => 'Profile'],
                ];
            @endphp
            @foreach($adminNav as $item)
                <a href="{{ route($item['route']) }}" data-tooltip="{{ $item['label'] }}"
                    class="nav-item {{ request()->routeIs($item['route']) ? 'nav-item-admin active' : '' }}">
                    <span class="nav-item-icon">{{ $item['icon'] }}</span>
                    <span class="nav-label">{{ $item['label'] }}</span>
                </a>
            @endforeach
        </nav>

        <div class="border-t border-red-900/20 p-3">
            <div class="nav-item" data-tooltip="Administrator">
                <span class="nav-item-icon text-red-400">⬡</span>
                <span class="nav-label text-xs text-red-400">Administrator</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" data-tooltip="Logout" class="nav-item w-full text-left hover:text-red-400 mt-1">
                    <span class="nav-item-icon">⇥</span>
                    <span class="nav-label">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="main-content relative z-10">
        @if(session('success'))
            <div class="flash-success mb-6">✓ {{ session('success') }}</div>
        @endif
        @yield('content')
    </main>

    @stack('scripts')
</body>

</html>