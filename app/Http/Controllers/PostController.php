<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Tag;
use App\Models\Category;
use App\Http\Requests\StorePostRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('category', 'tags')->latest()->get();
        return Inertia::render('Posts/Index', [
            'posts' => $posts,
            'categories' => Category::all(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Posts/Create', [
            'categories' => Category::all(),
            'allTags' => Tag::all(),
        ]);
    }

    public function store(StorePostRequest $request)
    {
        // Create the post first
        $post = Post::create($request->validated());
        
        // Handle tags - create new tags and save in tag table and pivot table
        if ($request->has('tags') && is_array($request->tags)) {
            $tagIds = [];
            
            foreach ($request->tags as $tagName) {
                if (empty(trim($tagName))) continue;
                
                // firstOrCreate checks if tag exists, if not creates it in tags table
                $tag = Tag::firstOrCreate(
                    ['name' => trim($tagName)],
                    ['name' => trim($tagName)]
                );
                
                $tagIds[] = $tag->id;
            }
            
            // attach() saves the relationship in post_tag pivot table
            if (!empty($tagIds)) {
                $post->tags()->attach($tagIds);
            }
        }

        return redirect()->route('posts.index')
            ->with('success', 'Post created successfully!');
    }

    public function edit(Post $post)
    {
        $post->load('category', 'tags');
        return Inertia::render('Posts/Edit', [
            'post' => $post,
            'categories' => Category::all(),
            'allTags' => Tag::all()
        ]);
    }

    public function update(StorePostRequest $request, Post $post)
    {
        // Update post basic fields
        $post->update($request->validated());
        
        // Handle tags
        if ($request->has('tags') && is_array($request->tags)) {
            $tagIds = [];
            
            foreach ($request->tags as $tagName) {
                if (empty(trim($tagName))) continue;
                
                // Create tag if it doesn't exist
                $tag = Tag::firstOrCreate(
                    ['name' => trim($tagName)],
                    ['description' => trim($tagName)]
                );
                
                $tagIds[] = $tag->id;
            }
            
            // sync() removes old tags and adds new ones in pivot table
            $post->tags()->sync($tagIds);
        } else {
            // Remove all tags if none provided
            $post->tags()->detach();
        }

        return redirect()->route('posts.index')
            ->with('success', 'Post updated successfully!');
    }

    public function delete(Post $post)
    {
        $post->delete();

        return redirect()->route('posts.index')
            ->with('success', 'Post deleted successfully!');
    }
}