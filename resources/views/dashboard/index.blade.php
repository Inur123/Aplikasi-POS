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
                    <p class="text-3xl font-bold text-gray-800">Rp {{ number_format($totalPenjualanHariIni, 0, ',', '.') }}
                    </p>
                    <p class="{{ $trendPenjualanClass }} text-sm mt-1">
                        <i class="fas {{ $trendPenjualanIcon }} mr-1"></i>
                        {{ $persentasePenjualan >= 0 ? '+' : '-' }}{{ $persentasePenjualanFormatted }}% dari kemarin
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
                    <p class="text-3xl font-bold text-gray-800">{{ $jumlahTransaksiHariIni }}</p>
                    <p class="{{ $trendTransaksiClass }} text-sm mt-1">
                        <i class="fas {{ $trendTransaksiIcon }} mr-1"></i>
                        {{ $persentaseTransaksi >= 0 ? '+' : '-' }}{{ $persentaseTransaksiFormatted }}% dari kemarin
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
                    <p class="text-3xl font-bold text-gray-800">{{ $totalProduk }}</p>
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

                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($transaksiTerbaru as $trx)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-blue-600">{{ $trx->invoice }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $trx->created_at->format('H:i') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $trx->transaction_items_count }} item(s)</td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">Rp
                                {{ number_format($trx->total_price, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Terjual</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($produkTerlaris as $produk)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 mr-3">
                                        @if ($produk->image)
                                            <img src="{{ asset('storage/' . $produk->image) }}" alt="{{ $produk->name }}"
                                                class="object-cover w-12 h-12 rounded-lg">
                                        @else
                                            <div
                                                class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500">
                                                <i class="fas fa-image"></i> {{-- Icon default jika tidak ada gambar --}}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $produk->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $produk->description }}</div>
                                    </div>
                                </div>

                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ $produk->category->name ?? 'Tanpa Kategori' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">Rp {{ number_format($produk->price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $produk->terjual_hari_ini ?? 0 }}</td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>
@endsection
