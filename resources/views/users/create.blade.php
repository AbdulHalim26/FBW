@extends('layouts.app')

@section('title', 'Tambah Pengguna')
@section('header', 'Tambah Akun Pengguna')

@section('content')
<div class="max-w-xl bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <form action="{{ route('users.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
            @error('name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
            @error('email') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Role / Hak Akses</label>
            <select name="role" required class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                <option value="kasir" @selected(old('role')==='kasir' )>Kasir</option>
                <option value="admin" @selected(old('role')==='admin' )>Admin</option>
            </select>
            @error('role') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input type="password" name="password" required
                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
            @error('password') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" required
                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
        </div>

        <div class="flex items-center gap-2 pt-2">
            <button type="submit"
                class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded-md cursor-pointer">
                Simpan Pengguna
            </button>
            <a href="{{ route('users.index') }}"
                class="bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm px-4 py-2 rounded-md">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection