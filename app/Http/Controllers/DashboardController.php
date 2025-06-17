<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TransactionItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {

        $user = Auth::user();
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $totalProduk = Product::count();
        $transaksiTerbaru = Transaction::withCount('transactionItems')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

     $produkTerlaris = Product::with('category')
    ->select('products.*')
    ->addSelect([
        'terjual_hari_ini' => TransactionItem::select(DB::raw('SUM(quantity)'))
            ->whereColumn('product_id', 'products.id')
            ->whereDate('created_at', $today)
    ])
    ->get();

        // Total penjualan hari ini dan kemarin
        $totalPenjualanHariIni = Transaction::whereDate('created_at', $today)->sum('total_price');
        $totalPenjualanKemarin = Transaction::whereDate('created_at', $yesterday)->sum('total_price');

        // Jumlah transaksi hari ini dan kemarin
        $jumlahTransaksiHariIni = Transaction::whereDate('created_at', $today)->count();
        $jumlahTransaksiKemarin = Transaction::whereDate('created_at', $yesterday)->count();

        // Hitung persentase perubahan penjualan
        $persentasePenjualan = $totalPenjualanKemarin > 0
            ? min((($totalPenjualanHariIni - $totalPenjualanKemarin) / $totalPenjualanKemarin) * 100, 100)
            : 0;

        $persentaseTransaksi = $jumlahTransaksiKemarin > 0
            ? min((($jumlahTransaksiHariIni - $jumlahTransaksiKemarin) / $jumlahTransaksiKemarin) * 100, 100)
            : 0;


        // Format
        $persentasePenjualanFormatted = number_format(abs($persentasePenjualan), 0);
        $trendPenjualanClass = $persentasePenjualan >= 0 ? 'text-green-600' : 'text-red-600';
        $trendPenjualanIcon = $persentasePenjualan >= 0 ? 'fa-arrow-up' : 'fa-arrow-down';

        $persentaseTransaksiFormatted = number_format(abs($persentaseTransaksi), 0);
        $trendTransaksiClass = $persentaseTransaksi >= 0 ? 'text-green-600' : 'text-red-600';
        $trendTransaksiIcon = $persentaseTransaksi >= 0 ? 'fa-arrow-up' : 'fa-arrow-down';

        return view('dashboard.index', compact(
            'user',
            'totalPenjualanHariIni',
            'jumlahTransaksiHariIni',
            'persentasePenjualan',
            'persentasePenjualanFormatted',
            'trendPenjualanClass',
            'trendPenjualanIcon',
            'persentaseTransaksi',
            'persentaseTransaksiFormatted',
            'trendTransaksiClass',
            'trendTransaksiIcon',
            'totalProduk',
            'transaksiTerbaru',
            'produkTerlaris'
        ));
    }
}
