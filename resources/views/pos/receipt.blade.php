@extends('layouts.app')

@section('title', 'Struk Transaksi')
@section('header', 'Struk Transaksi')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <div class="text-center mb-4">
        <h3 class="text-lg font-bold text-gray-900">POS Barokah Mart</h3>
        <p class="text-xs font-mono text-gray-500 mt-0.5">{{ $transaction->invoice_number }}</p>
        <p class="text-xs text-gray-500 mt-1">
            {{ $transaction->created_at->format('d M Y H:i') }} &middot; Kasir: {{ $transaction->user->name ?? '-' }}
        </p>
    </div>

    <hr class="my-3 border-dashed">

    <div class="space-y-1.5 text-sm">
        @foreach ($transaction->details as $detail)
        <div class="flex justify-between gap-2">
            <div>
                <p class="font-medium text-gray-900">{{ $detail->product->name ?? '-' }}</p>
                <p class="text-xs text-gray-500">
                    {{ $detail->qty }} &times; Rp {{ number_format($detail->price, 0, ',', '.') }}
                </p>
            </div>
            <span class="font-medium text-gray-900">
                Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
            </span>
        </div>
        @endforeach
    </div>

    <hr class="my-3 border-dashed">

    <div class="space-y-1 text-sm">
        <div class="flex justify-between font-bold text-base text-gray-900">
            <span>Total</span>
            <span>Rp {{ number_format($transaction->total, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between text-gray-600">
            <span>Bayar</span>
            <span>Rp {{ number_format($transaction->pay, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between text-emerald-700 font-semibold">
            <span>Kembalian</span>
            <span>Rp {{ number_format($transaction->change, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="mt-6 flex flex-col gap-2 print:hidden">
        <button type="button" onclick="window.print()"
            class="w-full bg-gray-800 hover:bg-gray-900 text-white text-sm font-medium py-2 rounded-md cursor-pointer">
            Cetak Struk
        </button>
        <div class="grid grid-cols-2 gap-2">
            <a href="{{ route('pos.index') }}"
                class="text-center bg-indigo-600 hover:bg-indigo-700 text-white text-sm py-2 rounded-md">
                Transaksi Baru
            </a>
            <a href="{{ route('pos.history') }}"
                class="text-center bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm py-2 rounded-md">
                Riwayat Saya
            </a>
        </div>
    </div>
</div>
@endsection