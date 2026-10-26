<article class="post-card">
    <h2>
        <a href="{{ route('posts.show', $post['id']) }}">
            {{ $post['title'] }}
        </a>
    </h2>

    <p class="meta">
        Oleh {{ $post['author'] }} | {{ $post['created_at'] }}
    </p>

    <p>{{ Str::limit($post['content'], 150) }}</p>

    {{-- Tombol dari slot --}}
    <div style="margin-top: 10px;">
        {{ $slot }}
    </div>
</article>
