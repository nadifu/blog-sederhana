@extends('layouts.app')

@section('title', $post['title'])

@section('content')
<article class="post-detail">
    <h1>{{ $post['title'] }}</h1>
    <p class="meta">
        Oleh {{ $post['author'] }} | {{ $post['created_at'] }}
    </p>
    <div class="content">
        {!! nl2br(e($post['content'])) !!}
    </div>
    
    {{-- 🔥 ACTION BUTTONS --}}
    <div style="display: flex; gap: 10px; margin-top: 30px;">
        <a href="{{ route('posts.index') }}" class="btn">
            Kembali
        </a>
        <a href="{{ route('posts.edit', $post['id']) }}" class="btn" style="background: #f39c12;">
            Edit Post
        </a>
        
        {{-- Form Delete --}}
        <form action="{{ route('posts.destroy', $post['id']) }}" method="POST" 
              onsubmit="return confirm('Yakin ingin menghapus post ini?')"
              style="display: inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn" style="background: #e74c3c;">
                Hapus Post
            </button>
        </form>
    </div>
</article>
@endsection