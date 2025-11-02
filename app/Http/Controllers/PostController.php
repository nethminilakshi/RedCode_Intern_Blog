<?php

namespace App\Http\Controllers;
use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->get();
        return Inertia::render('Posts/Index', [
            'posts' => $posts
        ]);
    }

     public function create()
    {
        return Inertia::render('Posts/Create');
    }

     public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        Post::create($validated);

        return redirect()->route('posts.index')
            ->with('success', 'Post created successfully!');
    }

    //  public function show(Post $post)
    // {
    //     return Inertia::render('Posts/Show', [
    //         'post' => $post
    //     ]);
    // }
}
