@extends('layouts.app')

@section('title', 'Data Kategori')
@section('header', 'Manajemen Kategori')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-base font-semibold text-gray-800">Daftar Kategori Produk</h3>
        <a href="{{ route('categories.create') }}"
            class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded-md">
            + Tambah Kategori
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left border-collapse">
            <thead>
                <tr class="border-b bg-gray-50 text-gray-600">
                    <th class="py-2.5 px-3">No</th>
                    <th class="py-2.5 px-3">Nama Kategori</th>
                    <th class="py-2.5 px-3">Deskripsi</th>
                    <th class="py-2.5 px-3">Jumlah Produk</th>
                    <th class="py-2.5 px-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($categories as $index => $category)
                <tr>
                    <td class="py-2.5 px-3">{{ $categories->firstItem() + $index }}</td>
                    <td class="py-2.5 px-3 font-medium text-gray-900">{{ $category->name }}</td>
                    <td class="py-2.5 px-3 text-gray-600">{{ $category->description ?? '-' }}</td>
                    <td class="py-2.5 px-3">{{ $category->products_count }}</td>
                    <td class="py-2.5 px-3 text-right space-x-2">
                        <a href="{{ route('categories.edit', $category) }}"
                            class="inline-block bg-amber-500 hover:bg-amber-600 text-white px-2.5 py-1 rounded text-xs">
                            Edit
                        </a>
                        <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline"
                            onsubmit="return confirm('Hapus kategori ini?')">
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
                    <td colspan="5" class="py-6 text-center text-gray-500">Belum ada data kategori.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $categories->links() }}
    </div>
</div>
@endsection