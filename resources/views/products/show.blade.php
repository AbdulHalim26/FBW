@extends('layouts.app')

@section('title', 'Detail Produk')
@section('header', 'Detail Produk & Riwayat Penjualan')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    {{-- Informasi Utama & Ringkasan --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex flex-wrap items-start justify-between gap-4 mb-6">
            <div class="flex items-center gap-4">
                @if ($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                    class="w-16 h-16 object-cover rounded-lg border">
                @endif
                <div>
                    <h3 class="text-xl font-bold text-gray-900">{{ $product->name }}</h3>
                    <p class="text-sm text-gray-500">
                        <span class="font-mono">{{ $product->code }}</span> &middot; {{ $product->category->name ?? '-' }}
                    </p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('products.edit', $product) }}"
                    class="bg-amber-500 hover:bg-amber-600 text-white text-sm px-3.5 py-1.5 rounded-md">
                    Edit Produk
                </a>
                <a href="{{ route('products.index') }}"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm px-3.5 py-1.5 rounded-md">
                    Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4 text-center">
                <p class="text-xs text-gray-500 mb-1">Harga Satuan</p>
                <p class="text-lg font-bold text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4 text-center">
                <p class="text-xs text-gray-500 mb-1">Stok Saat Ini</p>
                <p class="text-lg font-bold {{ $product->stock === 0 ? 'text-red-600' : ($product->stock < 10 ? 'text-amber-600' : 'text-gray-900') }}">
                    {{ $product->stock }} {{ $product->unit }}
                </p>
            </div>
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-4 text-center">
                <p class="text-xs text-gray-500 mb-1">Total Terjual</p>
                <p class="text-lg font-bold text-indigo-700">{{ $totalTerjual }} {{ $product->unit }}</p>
            </div>
        </div>
    </div>

    {{-- Riwayat Penjualan Terakhir --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h4 class="text-base font-semibold text-gray-800 mb-3">Riwayat Penjualan Terakhir (10 Transaksi)</h4>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead>
                    <tr class="border-b bg-gray-50 text-gray-600">
                        <th class="py-2 px-3">No. Invoice</th>
                        <th class="py-2 px-3">Tanggal</th>
                        <th class="py-2 px-3 text-right">Qty</th>
                        <th class="py-2 px-3 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($riwayat as $item)
                    <tr>
                        <td class="py-2 px-3 font-mono text-xs font-medium text-gray-900">
                            {{ $item->transaction->invoice_number ?? '-' }}
                        </td>
                        <td class="py-2 px-3 text-gray-600">
                            {{ $item->transaction?->created_at?->format('d M Y H:i') ?? '-' }}
                        </td>
                        <td class="py-2 px-3 text-right">{{ $item->qty }} {{ $product->unit }}</td>
                        <td class="py-2 px-3 text-right font-semibold">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-5 text-center text-gray-500">Belum pernah terjual.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Top 5 Hari Penjualan Tertinggi --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h4 class="text-base font-semibold text-gray-800 mb-3">5 Hari dengan Penjualan Tertinggi</h4>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse">
                <thead>
                    <tr class="border-b bg-gray-50 text-gray-600">
                        <th class="py-2 px-3">Peringkat</th>
                        <th class="py-2 px-3">Tanggal</th>
                        <th class="py-2 px-3 text-right">Total Qty Terjual</th>
                        <th class="py-2 px-3 text-right">Total Nilai Penjualan</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($topDays as $idx => $day)
                    <tr>
                        <td class="py-2 px-3 font-semibold">#{{ $idx + 1 }}</td>
                        <td class="py-2 px-3">{{ \Carbon\Carbon::parse($day->tanggal)->format('d M Y') }}</td>
                        <td class="py-2 px-3 text-right">{{ $day->total_qty }} {{ $product->unit }}</td>
                        <td class="py-2 px-3 text-right font-semibold">
                            Rp {{ number_format($day->total_subtotal, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-5 text-center text-gray-500">Belum ada data penjualan harian.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection