@extends('layouts.app')

@section('title', 'Data Produk')
@section('header', 'Manajemen Produk')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
        <form method="GET" action="{{ route('products.index') }}" class="flex flex-wrap items-center gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode / nama..."
                class="rounded-md border border-gray-300 px-3 py-1.5 text-sm">

            <select name="category_id" class="rounded-md border border-gray-300 px-3 py-1.5 text-sm">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" @selected(request('category_id')==$cat->id)>
                    {{ $cat->name }}
                </option>
                @endforeach
            </select>

            <select name="stock_status" class="rounded-md border border-gray-300 px-3 py-1.5 text-sm">
                <option value="">Semua Stok</option>
                <option value="aman" @selected(request('stock_status')==='aman' )>Aman (&ge; 10)</option>
                <option value="menipis" @selected(request('stock_status')==='menipis' )>Menipis (1–9)</option>
                <option value="habis" @selected(request('stock_status')==='habis' )>Habis (0)</option>
            </select>

            <select name="per_page" class="rounded-md border border-gray-300 px-3 py-1.5 text-sm">
                @foreach ([10, 25, 50] as $size)
                <option value="{{ $size }}" @selected((int) request('per_page', 10)===$size)>
                    {{ $size }} / hal
                </option>
                @endforeach
            </select>

            <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white text-sm px-3 py-1.5 rounded-md cursor-pointer">
                Filter
            </button>

            <a href="{{ route('products.index') }}"
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm px-3 py-1.5 rounded-md">
                Reset
            </a>
        </form>

        <a href="{{ route('products.create') }}"
            class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded-md">
            + Tambah Produk
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left border-collapse">
            <thead>
                <tr class="border-b bg-gray-50 text-gray-600">
                    <th class="py-2.5 px-3">Kode</th>
                    <th class="py-2.5 px-3">Produk</th>
                    <th class="py-2.5 px-3">Kategori</th>
                    <th class="py-2.5 px-3">Satuan</th>
                    <th class="py-2.5 px-3">Harga</th>
                    <th class="py-2.5 px-3">Stok</th>
                    <th class="py-2.5 px-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($products as $product)
                <tr class="@if ($product->stock === 0) bg-red-50 @elseif ($product->stock < 10) bg-yellow-50 @endif">
                    <td class="py-2.5 px-3 font-mono text-xs">{{ $product->code }}</td>
                    <td class="py-2.5 px-3 font-medium text-gray-900 flex items-center gap-2.5">
                        @if ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                            class="w-9 h-9 object-cover rounded border">
                        @endif
                        <span>{{ $product->name }}</span>
                    </td>
                    <td class="py-2.5 px-3">{{ $product->category->name ?? '-' }}</td>
                    <td class="py-2.5 px-3">{{ $product->unit }}</td>
                    <td class="py-2.5 px-3">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                    <td class="py-2.5 px-3 font-semibold">
                        {{ $product->stock }}
                        @if ($product->stock === 0)
                        <span class="ml-1 inline-block bg-red-100 text-red-700 text-[11px] font-medium px-1.5 py-0.5 rounded">Habis</span>
                        @elseif ($product->stock < 10)
                            <span class="ml-1 inline-block bg-yellow-100 text-yellow-700 text-[11px] font-medium px-1.5 py-0.5 rounded">Menipis</span>
                            @endif
                    </td>
                    <td class="py-2.5 px-3 text-right space-x-1.5 whitespace-nowrap">
                        <a href="{{ route('products.show', $product) }}"
                            class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-2.5 py-1 rounded text-xs">
                            Detail
                        </a>
                        <a href="{{ route('products.edit', $product) }}"
                            class="inline-block bg-amber-500 hover:bg-amber-600 text-white px-2.5 py-1 rounded text-xs">
                            Edit
                        </a>
                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline"
                            onsubmit="return confirm('Hapus produk ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white px-2.5 py-1 rounded text-xs cursor-pointer">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-6 text-center text-gray-500">Belum ada data produk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection