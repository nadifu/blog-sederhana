@extends('layouts.app')

@section('title', 'Semua Post')

@section('content')

    {{-- Tombol Create Post --}}
    @auth
        <a href="{{ route('posts.create') }}" class="btn" 
           style="margin-bottom:20px; display:inline-block;">
            + Buat Post Baru
        </a>
    @endauth

    @forelse($posts as $post)

        <x-post-card :post="$post">
            <div style="display: flex; gap: 10px; margin-top: 15px;">

                {{-- Baca --}}
                <a href="{{ route('posts.show', $post['id']) }}" class="btn">
                    Baca
                </a>

                {{-- Edit --}}
                @auth
                    <a href="{{ route('posts.edit', $post['id']) }}" class="btn" style="background:#f39c12;">
                        Edit
                    </a>
                @endauth

                {{-- Delete --}}
                @auth
                <form action="{{ route('posts.destroy', $post['id']) }}" method="POST"
                      onsubmit="return confirm('Yakin ingin menghapus post ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn" style="background:#e74c3c;">
                        Hapus
                    </button>
                </form>
                @endauth

            </div>
        </x-post-card>

    @empty

        <p>Belum ada post.</p>

        {{-- Tampilkan tombol buat post kalau user login --}}
        @auth
            <a href="{{ route('posts.create') }}" class="btn">
                + Buat Post Pertama
            </a>
        @endauth

    @endforelse

@endsection
