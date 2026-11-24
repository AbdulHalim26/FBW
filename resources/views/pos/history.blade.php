@extends('layouts.app')

@section('title', 'Riwayat Transaksi Saya')
@section('header', 'Riwayat Transaksi Saya')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <div class="flex justify-between items-center mb-4">
        <p class="text-sm text-gray-600">
            Daftar transaksi yang diproses oleh <strong>{{ auth()->user()->name }}</strong>.
        </p>
        <a href="{{ route('pos.index') }}"
            class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-3.5 py-1.5 rounded-md">
            + Transaksi Baru
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left border-collapse">
            <thead>
                <tr class="border-b bg-gray-50 text-gray-600">
                    <th class="py-2 px-3">No. Invoice</th>
                    <th class="py-2 px-3">Waktu</th>
                    <th class="py-2 px-3 text-center">Qty</th>
                    <th class="py-2 px-3 text-right">Total</th>
                    <th class="py-2 px-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($transactions as $trx)
                <tr>
                    <td class="py-2.5 px-3 font-mono text-xs font-medium">{{ $trx->invoice_number }}</td>
                    <td class="py-2.5 px-3 text-gray-600">{{ $trx->created_at->format('d M Y H:i') }}</td>
                    <td class="py-2.5 px-3 text-center">{{ $trx->total_qty ?? 0 }}</td>
                    <td class="py-2.5 px-3 text-right font-semibold">
                        Rp {{ number_format($trx->total, 0, ',', '.') }}
                    </td>
                    <td class="py-2.5 px-3 text-right">
                        <a href="{{ route('pos.receipt', $trx) }}"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white px-2.5 py-1 rounded text-xs">
                            Struk
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-6 text-center text-gray-500">Belum ada riwayat transaksi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $transactions->links() }}</div>
</div>
@endsection