@extends('layouts.app')

@section('title', 'Dashboard')
@section('header', 'Dashboard Analitik POS Barokah Mart')

@section('content')
<div class="space-y-6">
    {{-- 4 Kartu Ringkasan KPI --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
            <p class="text-xs font-medium text-gray-500 uppercase">Omzet Hari Ini</p>
            <p class="text-2xl font-bold text-indigo-700 mt-1">
                Rp {{ number_format($omzetHariIni, 0, ',', '.') }}
            </p>
            <p class="text-xs text-gray-400 mt-1">{{ now()->format('d M Y') }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
            <p class="text-xs font-medium text-gray-500 uppercase">Transaksi Hari Ini</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $transaksiHariIni }}</p>
            <p class="text-xs text-gray-400 mt-1">Struk tercatat hari ini</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
            <p class="text-xs font-medium text-gray-500 uppercase">Total Produk</p>
            <p class="text-2xl font-bold text-emerald-700 mt-1">{{ $totalProduk }}</p>
            <p class="text-xs text-gray-400 mt-1">Item terdaftar di katalog</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5">
            <p class="text-xs font-medium text-gray-500 uppercase">Stok Menipis (&lt; 10)</p>
            <p class="text-2xl font-bold {{ $stokMenipisCount > 0 ? 'text-amber-600' : 'text-gray-900' }} mt-1">
                {{ $stokMenipisCount }}
            </p>
            <p class="text-xs text-gray-400 mt-1">Perlu segera restock</p>
        </div>
    </div>

    {{-- Grafik Chart.js --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-semibold text-gray-800">Tren Penjualan 7 Hari Terakhir</h3>
                <span class="text-xs text-gray-400">Dalam Rupiah (Rp)</span>
            </div>
            <div class="h-64">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-semibold text-gray-800">Top 5 Produk Terlaris</h3>
                <span class="text-xs text-gray-400">Unit Terjual</span>
            </div>
            <div class="h-64 flex items-center justify-center">
                @if (count($topLabels) > 0)
                <canvas id="topProductsChart"></canvas>
                @else
                <p class="text-sm text-gray-400">Belum ada data penjualan produk.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Tabel Stok Menipis & Transaksi Terbaru --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-base font-semibold text-gray-800 mb-3">Peringatan Stok Menipis</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead>
                        <tr class="border-b bg-gray-50 text-gray-600">
                            <th class="py-2 px-3">Kode</th>
                            <th class="py-2 px-3">Produk</th>
                            <th class="py-2 px-3">Kategori</th>
                            <th class="py-2 px-3 text-right">Sisa Stok</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse ($lowStockProducts as $item)
                        <tr>
                            <td class="py-2 px-3 font-mono text-xs">{{ $item->code }}</td>
                            <td class="py-2 px-3 font-medium text-gray-900">{{ $item->name }}</td>
                            <td class="py-2 px-3 text-gray-500">{{ $item->category->name ?? '-' }}</td>
                            <td class="py-2 px-3 text-right font-semibold {{ $item->stock === 0 ? 'text-red-600' : 'text-amber-600' }}">
                                {{ $item->stock }} {{ $item->unit }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-5 text-center text-gray-500">Semua stok produk dalam kondisi aman.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-base font-semibold text-gray-800 mb-3">5 Transaksi Terakhir</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead>
                        <tr class="border-b bg-gray-50 text-gray-600">
                            <th class="py-2 px-3">No. Invoice</th>
                            <th class="py-2 px-3">Kasir</th>
                            <th class="py-2 px-3">Waktu</th>
                            <th class="py-2 px-3 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse ($recentTransactions as $trx)
                        <tr>
                            <td class="py-2 px-3 font-mono text-xs font-medium text-gray-900">{{ $trx->invoice_number }}</td>
                            <td class="py-2 px-3 text-gray-600">{{ $trx->user->name ?? '-' }}</td>
                            <td class="py-2 px-3 text-gray-500">{{ $trx->created_at->format('d M H:i') }}</td>
                            <td class="py-2 px-3 text-right font-semibold">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-5 text-center text-gray-500">Belum ada transaksi tercatat.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const salesCtx = document.getElementById('salesChart');
        if (salesCtx && window.Chart) {
            new Chart(salesCtx, {
                type: 'bar',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: 'Total Penjualan (Rp)',
                        data: @json($chartTotals),
                        backgroundColor: 'rgba(79, 70, 229, 0.8)',
                        borderColor: 'rgb(79, 70, 229)',
                        borderWidth: 1,
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: (val) => 'Rp ' + Number(val).toLocaleString('id-ID')
                            }
                        }
                    }
                }
            });
        }

        const topCtx = document.getElementById('topProductsChart');
        if (topCtx && window.Chart) {
            new Chart(topCtx, {
                type: 'doughnut',
                data: {
                    labels: @json($topLabels),
                    datasets: [{
                        data: @json($topValues),
                        backgroundColor: ['#4F46E5', '#10B981', '#F59E0B', '#EF4444', '#6366F1']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    });
</script>
@endpush