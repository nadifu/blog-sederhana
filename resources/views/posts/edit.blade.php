@extends('layouts.app')

@section('title', 'Edit Post')

@section('content')

<div class="post-card" style="padding: 30px;">

    <h2 class="text-xl font-semibold mb-5">Edit Post</h2>

    <form action="{{ route('posts.update', $post['id']) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        {{-- JUDUL --}}
        <div class="form-group">
            <label for="title" class="block font-medium mb-1">Judul</label>

            <input
                type="text"
                name="title"
                id="title"
                value="{{ old('title', $post['title']) }}"
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
                value="{{ old('author', $post['author']) }}"
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
            >{{ old('content', $post['content']) }}</textarea>

            @error('content')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- BUTTON --}}
        <div style="display: flex; gap: 10px; margin-top: 15px;">
            <button type="submit" class="btn">
                Update Post
            </button>

            <a href="{{ route('posts.show', $post['id']) }}" 
               class="btn"
               style="background: #777;">
               Batal
            </a>
        </div>

    </form>
</div>

@endsection
