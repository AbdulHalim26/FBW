<!DOCTYPE html>
<htm lang="id">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Dashboard') - POS Barokah Mart</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body class="bg-gray-100 text-gray-800 antialiased">
        <div class="min-h-screen flex">
            {{-- Sidebar --}}
            <aside class="w-64 bg-indigo-900 text-white flex flex-col shrink-0">
                <div class="p-6 border-b border-indigo-800">
                    <h1 class="text-lg font-bold tracking-wide">Barokah Mart</h1>
                    <p class="text-xs text-indigo-300 mt-0.5">Point of Sale System</p>
                </div>

                <nav class="flex-1 p-4 space-y-1 text-sm">
                    <a href="{{ route('dashboard') }}"
                        class="block px-4 py-2.5 rounded-md {{ request()->routeIs('dashboard') ? 'bg-indigo-700 font-semibold' : 'hover:bg-indigo-800' }}">
                        Dashboard
                    </a>

                    <a href="{{ route('pos.index') }}"
                        class="block px-4 py-2.5 rounded-md {{ request()->routeIs('pos.*') ? 'bg-indigo-700 font-semibold' : 'hover:bg-indigo-800' }}">
                        Kasir (POS)
                    </a>

                    @if (auth()->user()->role === 'admin')
                    <div class="pt-4 pb-1 px-4 text-xs uppercase tracking-wider text-indigo-400">
                        Manajemen Toko
                    </div>
                    <a href="{{ route('categories.index') }}"
                        class="block px-4 py-2.5 rounded-md {{ request()->routeIs('categories.*') ? 'bg-indigo-700 font-semibold' : 'hover:bg-indigo-800' }}">
                        Kategori
                    </a>
                    <a href="{{ route('products.index') }}"
                        class="block px-4 py-2.5 rounded-md {{ request()->routeIs('products.*') ? 'bg-indigo-700 font-semibold' : 'hover:bg-indigo-800' }}">
                        Produk
                    </a>
                    <a href="{{ route('users.index') }}"
                        class="block px-4 py-2.5 rounded-md {{ request()->routeIs('users.*') ? 'bg-indigo-700 font-semibold' : 'hover:bg-indigo-800' }}">
                        Pengguna
                    </a>
                    <a href="{{ route('report.sales') }}"
                        class="block px-4 py-2.5 rounded-md {{ request()->routeIs('report.*') ? 'bg-indigo-700 font-semibold' : 'hover:bg-indigo-800' }}">
                        Laporan Penjualan
                    </a>
                    @endif
                </nav>

                <div class="p-4 border-t border-indigo-800 text-xs text-indigo-300">
                    &copy; {{ date('Y') }} Barokah Mart POS
                </div>
            </aside>

            {{-- Main Content --}}
            <div class="flex-1 flex flex-col">
                {{-- Topbar --}}
                <header class="bg-white shadow-sm px-6 py-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-800">
                        @yield('header', 'Dashboard')
                    </h2>

                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-800">{{ auth()->user()->name }}</p>
                            <span class="inline-block text-xs px-2 py-0.5 rounded bg-indigo-100 text-indigo-700 capitalize">
                                {{ auth()->user()->role }}
                            </span>
                        </div>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="text-sm bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-md cursor-pointer">
                                Logout
                            </button>
                        </form>
                    </div>
                </header>

                {{-- Page Content --}}
                <main class="p-6 flex-1">
                    @if (session('success'))
                    <div class="mb-4 p-4 rounded-md bg-green-100 border border-green-300 text-green-800 text-sm">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if (session('error'))
                    <div class="mb-4 p-4 rounded-md bg-red-100 border border-red-300 text-red-800 text-sm">
                        {{ session('error') }}
                    </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>
        @stack('scripts')
    </body>

    </html>