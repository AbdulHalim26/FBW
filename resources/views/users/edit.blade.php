@extends('layouts.app')

@section('title', 'Edit Pengguna')
@section('header', 'Edit Akun Pengguna')

@section('content')
<div class="max-w-xl bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
            @error('name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
            @error('email') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Role / Hak Akses</label>
            <select name="role" required class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
                <option value="kasir" @selected(old('role', $user->role) === 'kasir')>Kasir</option>
                <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
            </select>
            @error('role') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Password Baru <span class="text-xs text-gray-400">(kosongkan jika tidak ingin mengubah)</span>
            </label>
            <input type="password" name="password"
                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
            @error('password') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation"
                class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm">
        </div>

        <div class="flex items-center gap-2 pt-2">
            <button type="submit"
                class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded-md cursor-pointer">
                Perbarui Pengguna
            </button>
            <a href="{{ route('users.index') }}"
                class="bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm px-4 py-2 rounded-md">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection