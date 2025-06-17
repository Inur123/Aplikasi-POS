@extends('layouts.app')

@section('title', 'Tambah Kategori Baru')
@section('subtitle', 'Buat kategori produk baru')

@section('content')
<div class="bg-white rounded-xl shadow-lg p-6 max-w-2xl mx-auto">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Tambah Kategori Baru</h2>

    <form action="{{ route('categories.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori</label>
            <input type="text" name="name" id="name"
                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                   value="{{ old('name') }}"
                   required>
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end space-x-3 mt-6">
            <a href="{{ route('categories.index') }}"
               class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                Batal
            </a>
            <button type="submit"
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                Simpan Kategori
            </button>
        </div>
    </form>
</div>
@endsection
