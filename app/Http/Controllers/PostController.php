<?php

namespace App\Http\Controllers;
use App\Models\Post;
use App\Http\Requests\StorePostRequest;
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


    // create form
     public function create()
    {
        return Inertia::render('Posts/Create');
    }

     public function store(StorePostRequest $request)
    {
            Post::create($request->validated());


            return redirect()->route('posts.index')
                ->with('success', 'Post created successfully!');
    }
// show edit form
    public function edit(Post $post){
        return Inertia::render('Posts/Edit',[
            'post' => $post

        ]);
    }

    public function update(StorePostRequest $request, Post $post)
    {
       $post->update($request->validated());

        return redirect()->route('posts.index')
            ->with('success', 'Post updated successfully!');
    }

    public function delete(Post $post){
        $post->delete();

        return redirect()->route('posts.index')
            ->with('success', 'Post deleted successfully!');
    }

}
