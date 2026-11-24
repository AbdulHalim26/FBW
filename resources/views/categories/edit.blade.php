@extends('layouts.app')

@section('title', 'Edit Kategori')
@section('header', 'Edit Kategori')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 max-w-lg">
    <form action="{{ route('categories.update', $category) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
            @error('name')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi (Opsional)</label>
            <textarea name="description" rows="3"
                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">{{ old('description', $category->description) }}</textarea>
            @error('description')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-2 pt-2">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded-md cursor-pointer">
                Perbarui
            </button>
            <a href="{{ route('categories.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm px-4 py-2 rounded-md">
                Kembali
            </a>
        </div>
    </form>
</div>
@endsection