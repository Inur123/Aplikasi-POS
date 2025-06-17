@extends('layouts.app')

@section('title', 'Edit Produk')
@section('subtitle', 'Perbarui data produk')

@section('content')
<div class="bg-white rounded-xl shadow-lg p-6 max-w-3xl mx-auto">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Edit Produk: {{ $product->name }}</h2>

    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
                <input type="text" name="name" id="name"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                       value="{{ old('name', $product->name) }}"
                       required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                <select name="category_id" id="category_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                        required>
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
            <textarea name="description" id="description" rows="3"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500">{{ old('description', $product->description) }}</textarea>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="mb-4">
                <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Harga</label>
                <input type="number" name="price" id="price" step="0.01" min="0"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                       value="{{ old('price', $product->price) }}"
                       required>
                @error('price')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Gambar Produk</label>
                <input type="file" name="image" id="image"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-orange-500 focus:border-orange-500"
                       accept="image/*">
                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                @if($product->image)
                <div class="mt-2">
                    <p class="text-sm text-gray-500 mb-1">Gambar saat ini:</p>
                    <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="h-20 w-20 object-cover rounded-md">
                </div>
                @endif
            </div>
        </div>

       <div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">Status Produk</label>
    <div class="flex items-center">
        <input type="hidden" name="active" value="0"> <!-- Hidden field for false value -->
        <label class="relative inline-flex items-center cursor-pointer">
            <input type="checkbox" name="active" id="active" value="1" class="sr-only peer"
                   {{ old('active', $product->active) ? 'checked' : '' }}>
            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500"></div>
            <span class="ml-3 text-sm font-medium text-gray-700" id="statusText">
                {{ old('active', $product->active) ? 'Aktif' : 'Nonaktif' }}
            </span>
        </label>
    </div>
    @error('active')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

        <div class="flex justify-end space-x-3 mt-6">
            <a href="{{ route('products.index') }}"
               class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                Batal
            </a>
            <button type="submit"
                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                Perbarui Produk
            </button>
        </div>
    </form>
</div>
<script>
    // Update the status text when toggle is clicked
    document.getElementById('active').addEventListener('change', function() {
        const statusText = document.getElementById('statusText');
        statusText.textContent = this.checked ? 'Aktif' : 'Nonaktif';
    });
</script>
@endsection
