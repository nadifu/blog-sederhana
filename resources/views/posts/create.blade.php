@extends('layouts.app')

@section('title', 'Tulis Post Baru')

@section('content')

<div class="post-card" style="padding: 30px;">

    <h2 class="text-xl font-semibold mb-5">Tulis Post Baru</h2>

    <form action="{{ route('posts.store') }}" method="POST" class="space-y-5">
        @csrf

        {{-- JUDUL --}}
        <div class="form-group">
            <label for="title" class="block font-medium mb-1">Judul</label>

            <input 
                type="text" 
                name="title" 
                id="title"
                value="{{ old('title') }}" 
                required
                class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200"
            >

            @error('title')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- PENULIS --}}
        <div class="form-group">
            <label for="author" class="block font-medium mb-1">Penulis</label>

            <input 
                type="text" 
                name="author" 
                id="author"
                value="{{ old('author') }}" 
                required
                class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200"
            >

            @error('author')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- KONTEN --}}
        <div class="form-group">
            <label for="content" class="block font-medium mb-1">Konten</label>

            <textarea 
                name="content" 
                id="content" 
                rows="8" 
                required
                class="w-full border rounded px-3 py-2 focus:ring focus:ring-blue-200"
            >{{ old('content') }}</textarea>

            @error('content')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- BUTTON --}}
        <button type="submit" class="btn">
            Simpan Post
        </button>

    </form>
</div>

@endsection
