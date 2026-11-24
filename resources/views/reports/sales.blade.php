@extends('layouts.app')

@section('title', 'Laporan Penjualan')
@section('header', 'Laporan Penjualan')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <form method="GET" action="{{ route('report.sales') }}" class="flex flex-wrap items-end gap-3 mb-6">
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Dari Tanggal</label>
            <input type="date" name="start" value="{{ $start->format('Y-m-d') }}"
                class="rounded-md border border-gray-300 px-3 py-1.5 text-sm">
        </div>

        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Sampai Tanggal</label>
            <input type="date" name="end" value="{{ $end->format('Y-m-d') }}"
                class="rounded-md border border-gray-300 px-3 py-1.5 text-sm">
        </div>

        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Kasir</label>
            <select name="user_id" class="rounded-md border border-gray-300 px-3 py-1.5 text-sm">
                <option value="">Semua Kasir</option>
                @foreach ($cashiers as $cashier)
                <option value="{{ $cashier->id }}" @selected($userId==$cashier->id)>
                    {{ $cashier->name }} ({{ ucfirst($cashier->role) }})
                </option>
                @endforeach
            </select>
        </div>

        <button type="submit"
            class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-1.5 rounded-md cursor-pointer">
            Tampilkan
        </button>

        <a href="{{ route('report.sales') }}"
            class="bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm px-3 py-1.5 rounded-md">
            Reset
        </a>

        <a href="{{ route('report.export', request()->query()) }}"
            class="ml-auto bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-4 py-1.5 rounded-md">
            Export Excel (.xlsx)
        </a>
    </form>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
        <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
            <p class="text-xs text-gray-500">Total Penjualan</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">
                Rp {{ number_format($totalPenjualan, 0, ',', '.') }}
            </p>
        </div>
        <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
            <p class="text-xs text-gray-500">Jumlah Transaksi</p>
            <p class="text-2xl font-bold text-indigo-700 mt-1">{{ $jumlahTransaksi }}</p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left border-collapse">
            <thead>
                <tr class="border-b bg-gray-50 text-gray-600">
                    <th class="py-2.5 px-3">No. Invoice</th>
                    <th class="py-2.5 px-3">Tanggal</th>
                    <th class="py-2.5 px-3">Kasir</th>
                    <th class="py-2.5 px-3 text-right">Total</th>
                    <th class="py-2.5 px-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($transactions as $trx)
                <tr>
                    <td class="py-2.5 px-3 font-mono text-xs font-medium text-gray-900">
                        {{ $trx->invoice_number }}
                    </td>
                    <td class="py-2.5 px-3 text-gray-600">
                        {{ $trx->created_at->format('d M Y H:i') }}
                    </td>
                    <td class="py-2.5 px-3">{{ $trx->user->name ?? '-' }}</td>
                    <td class="py-2.5 px-3 text-right font-semibold">
                        Rp {{ number_format($trx->total, 0, ',', '.') }}
                    </td>
                    <td class="py-2.5 px-3 text-right">
                        <a href="{{ route('pos.receipt', $trx) }}"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-2.5 py-1 rounded text-xs">
                            Lihat Struk
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-6 text-center text-gray-500">
                        Tidak ada transaksi pada rentang tanggal ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection