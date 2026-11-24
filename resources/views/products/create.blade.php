@extends('layouts.app')

@section('title', 'Tambah Produk')
@section('header', 'Tambah Produk Baru')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 max-w-xl">
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Barang</label>
                <input type="text" name="code" value="{{ old('code') }}" placeholder="BRG001" required
                    class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                @error('code') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                <select name="category_id" required class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" @selected(old('category_id')==$cat->id)>
                        {{ $cat->name }}
                    </option>
                    @endforeach
                </select>
                @error('category_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
            @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                <input type="text" name="unit" value="{{ old('unit', 'pcs') }}" required
                    class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                @error('unit') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
                <input type="number" name="price" value="{{ old('price', 0) }}" min="0" required
                    class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                @error('price') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Stok Awal</label>
                <input type="number" name="stock" value="{{ old('stock', 0) }}" min="0" required
                    class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                @error('stock') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Foto Produk (Opsional)</label>
            <input type="file" name="image" accept="image/*"
                class="w-full text-sm text-gray-600 file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:bg-indigo-50 file:text-indigo-700">
            @error('image') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center gap-2 pt-2">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded-md cursor-pointer">
                Simpan Produk
            </button>
            <a href="{{ route('products.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm px-4 py-2 rounded-md">
                Kembali
            </a>
        </div>
    </form>
</div>
@endsection