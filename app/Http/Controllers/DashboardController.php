<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // 1. Kartu Ringkasan KPI
        $omzetHariIni = Transaction::whereDate('created_at', today())->sum('total');
        $transaksiHariIni = Transaction::whereDate('created_at', today())->count();
        $totalProduk = Product::count();
        $stokMenipisCount = Product::where('stock', '<', 10)->count();

        // 2. Data Grafik Tren Penjualan 7 Hari Terakhir
        $rawSales = Transaction::selectRaw('DATE(created_at) as tanggal, SUM(total) as total_omzet')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('tanggal')
            ->pluck('total_omzet', 'tanggal');

        $chartLabels = [];
        $chartTotals = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $key = $date->format('Y-m-d');
            $chartLabels[] = $date->format('d M');
            $chartTotals[] = (int) ($rawSales[$key] ?? 0);
        }

        // 3. Data Grafik Top 5 Produk Terlaris
        $topProducts = DB::table('transaction_details')
            ->join('products', 'products.id', '=', 'transaction_details.product_id')
            ->select('products.name', DB::raw('SUM(transaction_details.qty) as total_qty'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        $topLabels = $topProducts->pluck('name')->toArray();
        $topValues = $topProducts->pluck('total_qty')->map(fn($v) => (int) $v)->toArray();

        // 4. Tabel Stok Menipis (< 10) & 5 Transaksi Terakhir
        $lowStockProducts = Product::with('category')
            ->where('stock', '<', 10)
            ->orderBy('stock')
            ->take(5)
            ->get();

        $recentTransactions = Transaction::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'omzetHariIni',
            'transaksiHariIni',
            'totalProduk',
            'stokMenipisCount',
            'chartLabels',
            'chartTotals',
            'topLabels',
            'topValues',
            'lowStockProducts',
            'recentTransactions'
        ));
    }
}
