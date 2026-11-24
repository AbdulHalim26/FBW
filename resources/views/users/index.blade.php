@extends('layouts.app')

@section('title', 'Manajemen Pengguna')
@section('header', 'Manajemen Pengguna & Hak Akses')

@section('content')
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
        <form method="GET" action="{{ route('users.index') }}" class="flex flex-wrap items-center gap-2">
            <input type="text" name="q" value="{{ $search }}" placeholder="Cari nama / email..."
                class="rounded-md border border-gray-300 px-3 py-1.5 text-sm w-56">

            <select name="role" class="rounded-md border border-gray-300 px-3 py-1.5 text-sm">
                <option value="">Semua Role</option>
                <option value="admin" @selected($role==='admin' )>Admin</option>
                <option value="kasir" @selected($role==='kasir' )>Kasir</option>
            </select>

            <button type="submit"
                class="bg-gray-800 hover:bg-gray-900 text-white text-sm px-3.5 py-1.5 rounded-md cursor-pointer">
                Filter
            </button>

            @if ($search || $role)
            <a href="{{ route('users.index') }}"
                class="text-xs text-gray-500 hover:text-gray-800 px-2 py-1.5">
                Reset
            </a>
            @endif
        </form>

        <a href="{{ route('users.create') }}"
            class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm px-4 py-2 rounded-md font-medium">
            + Tambah Pengguna
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left border-collapse">
            <thead>
                <tr class="border-b bg-gray-50 text-gray-600">
                    <th class="py-2.5 px-3">Nama</th>
                    <th class="py-2.5 px-3">Email</th>
                    <th class="py-2.5 px-3">Role</th>
                    <th class="py-2.5 px-3">Terdaftar</th>
                    <th class="py-2.5 px-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($users as $u)
                <tr>
                    <td class="py-2.5 px-3 font-medium text-gray-900">
                        {{ $u->name }}
                        @if (auth()->id() === $u->id)
                        <span class="ml-1.5 text-[11px] bg-gray-100 text-gray-600 px-1.5 py-0.5 rounded">Anda</span>
                        @endif
                    </td>
                    <td class="py-2.5 px-3 text-gray-600">{{ $u->email }}</td>
                    <td class="py-2.5 px-3">
                        @if ($u->role === 'admin')
                        <span class="inline-block bg-indigo-100 text-indigo-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                            Admin
                        </span>
                        @else
                        <span class="inline-block bg-emerald-100 text-emerald-800 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                            Kasir
                        </span>
                        @endif
                    </td>
                    <td class="py-2.5 px-3 text-gray-500">{{ $u->created_at?->format('d/m/Y') }}</td>
                    <td class="py-2.5 px-3 text-right space-x-1.5">
                        <a href="{{ route('users.edit', $u) }}"
                            class="inline-block bg-amber-500 hover:bg-amber-600 text-white px-2.5 py-1 rounded text-xs">
                            Edit
                        </a>

                        @if (auth()->id() !== $u->id)
                        <form action="{{ route('users.destroy', $u) }}" method="POST" class="inline"
                            onsubmit="return confirm('Yakin ingin menghapus akun {{ $u->name }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-rose-600 hover:bg-rose-700 text-white px-2.5 py-1 rounded text-xs cursor-pointer">
                                Hapus
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-6 text-center text-gray-500">Belum ada data pengguna.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection