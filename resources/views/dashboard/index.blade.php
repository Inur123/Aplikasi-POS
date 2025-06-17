@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Selamat datang di panel admin')

@section('content')
    <!-- Stats Cards -->
   <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl p-6 shadow-lg border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Penjualan Hari Ini</p>
                    <p class="text-3xl font-bold text-gray-800">Rp 2.150.000</p>
                    <p class="text-green-600 text-sm mt-1">
                        <i class="fas fa-arrow-up mr-1"></i>+12% dari kemarin
                    </p>
                </div>
                <div class="bg-blue-100 p-4 rounded-full">
                    <i class="fas fa-money-bill-wave text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-lg border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Transaksi Hari Ini</p>
                    <p class="text-3xl font-bold text-gray-800">47</p>
                    <p class="text-green-600 text-sm mt-1">
                        <i class="fas fa-arrow-up mr-1"></i>+8% dari kemarin
                    </p>
                </div>
                <div class="bg-green-100 p-4 rounded-full">
                    <i class="fas fa-shopping-cart text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-lg border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Produk</p>
                    <p class="text-3xl font-bold text-gray-800">24</p>
                    <p class="text-blue-600 text-sm mt-1">
                        <i class="fas fa-plus mr-1"></i>2 produk baru
                    </p>
                </div>
                <div class="bg-purple-100 p-4 rounded-full">
                    <i class="fas fa-box text-purple-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="bg-white rounded-xl shadow-lg mb-8">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800">Transaksi Terbaru</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Items</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-blue-600">TRX001</td>
                        <td class="px-6 py-4 text-sm text-gray-900">14:30</td>
                        <td class="px-6 py-4 text-sm text-gray-900">3 item(s)</td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">Rp 44.000</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Selesai
                            </span>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-medium text-blue-600">TRX002</td>
                        <td class="px-6 py-4 text-sm text-gray-900">15:45</td>
                        <td class="px-6 py-4 text-sm text-gray-900">1 item(s)</td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">Rp 22.000</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Selesai
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-xl shadow-lg">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-800">Produk Terpopuler</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <span class="text-2xl mr-3">🍛</span>
                                <div>
                                    <div class="font-medium text-gray-900">Nasi Gudeg Yogya</div>
                                    <div class="text-sm text-gray-500">Gudeg khas Yogya dengan ayam dan telur</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                Makanan
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">Rp 15.000</td>
                        <td class="px-6 py-4 text-sm text-gray-900">50</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <span class="text-2xl mr-3">🍗</span>
                                <div>
                                    <div class="font-medium text-gray-900">Ayam Bakar Bumbu Kecap</div>
                                    <div class="text-sm text-gray-500">Ayam bakar dengan bumbu kecap manis</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                Makanan
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">Rp 20.000</td>
                        <td class="px-6 py-4 text-sm text-gray-900">30</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
