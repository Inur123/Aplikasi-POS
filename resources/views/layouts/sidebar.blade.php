<div class="fixed inset-y-0 left-0 w-64 bg-white shadow-2xl z-50">
    <div class="flex items-center justify-center h-20 gradient-bg text-white">
        <div class="flex items-center space-x-3">
            <i class="fas fa-utensils text-2xl"></i>
            <span class="text-xl font-bold">Admin Panel</span>
        </div>
    </div>

    <nav class="mt-8">
        <div class="px-4 space-y-2">
            <a href="{{ route('dashboard') }}"
                class="sidebar-link flex items-center px-4 py-3 rounded-lg transition-all duration-300 {{ request()->routeIs('dashboard') ? 'sidebar-active text-gray-900' : 'text-gray-600 hover:bg-gray-100' }}">
                <i class="fas fa-tachometer-alt mr-3"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('products.index') }}"
                class="sidebar-link flex items-center px-4 py-3 rounded-lg transition-all duration-300 {{ request()->routeIs('products.*') ? 'sidebar-active text-gray-900' : 'text-gray-600 hover:bg-gray-100' }}">
                <i class="fas fa-box mr-3"></i>
                <span>Kelola Produk</span>
            </a>
            <a href="{{ route('categories.index') }}"
                class="sidebar-link flex items-center px-4 py-3 rounded-lg transition-all duration-300 {{ request()->routeIs('categories.*') ? 'sidebar-active text-gray-900' : 'text-gray-600 hover:bg-gray-100' }}">
                <i class="fas fa-tags mr-3"></i>
                <span>Category</span>
            </a>
            <a href="#"
                class="sidebar-link flex items-center px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-lg transition-all duration-300">
                <i class="fas fa-receipt mr-3"></i>
                <span>Data Transaksi</span>
            </a>
            <a href="#"
                class="sidebar-link flex items-center px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-lg transition-all duration-300">
                <i class="fas fa-chart-bar mr-3"></i>
                <span>Laporan</span>
            </a>
            <a href="#"
                class="sidebar-link flex items-center px-4 py-3 text-gray-600 hover:bg-gray-100 rounded-lg transition-all duration-300">
                <i class="fas fa-cog mr-3"></i>
                <span>Pengaturan</span>
            </a>
            <a href="{{ route('transactions.index') }}"
                class="sidebar-link flex items-center px-4 py-3 rounded-lg transition-all duration-300 {{ request()->routeIs('transactions.*') ? 'sidebar-active text-gray-900' : 'text-gray-600 hover:bg-gray-100' }}">
                <i class="fas fa-receipt mr-3"></i>
                <span>Transaksi</span>
            </a>

        </div>
    </nav>

    @auth
        <div class="absolute bottom-4 left-4 right-4">
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-10 h-10 bg-orange-500 rounded-full flex items-center justify-center text-white font-bold text-xs">
                        {{ substr(Auth::user()->name, 0, 1) }}{{ substr(explode(' ', Auth::user()->name)[1] ?? '', 0, 1) }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                        <p class="text-sm text-gray-600">{{ Auth::user()->role }}</p>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full mt-3 bg-red-500 text-white py-2 rounded-lg hover:bg-red-600 transition-colors">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    @endauth

</div>
