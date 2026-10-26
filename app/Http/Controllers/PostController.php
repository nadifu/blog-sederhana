<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Tampilkan daftar postingan blog.
     */
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->get();
        return view('posts.index', compact('posts'));
    }

    /**
     * Tampilkan form untuk membuat postingan baru.
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Simpan postingan baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'   => 'required|max:255',
            'content' => 'required',
            'author'  => 'required|max:100'
        ]);

        if (auth()->check()) {
            $validated['user_id'] = auth()->id();
        }

        Post::create($validated);

        return redirect()->route('posts.index')
            ->with('success', 'Post berhasil dibuat!');
    }

    /**
     * Tampilkan detail postingan tertentu.
     */
    public function show($id)
    {
        $post = Post::findOrFail($id);
        return view('posts.show', compact('post'));
    }

    /**
     * Tampilkan form edit postingan.
     */
    public function edit($id)
    {
        $post = Post::findOrFail($id);
        return view('posts.edit', compact('post'));
    }

    /**
     * Update postingan di database.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title'   => 'required|max:255',
            'content' => 'required',
            'author'  => 'required|max:100'
        ]);

        $post = Post::findOrFail($id);
        $post->update($validated);

        return redirect()->route('posts.show', $id)
            ->with('success', 'Post berhasil diperbarui!');
    }

    /**
     * Hapus postingan dari database.
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return redirect()->route('posts.index')
            ->with('success', 'Post berhasil dihapus!');
    }
}
