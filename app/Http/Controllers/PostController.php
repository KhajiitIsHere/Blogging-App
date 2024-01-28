<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {

        return view('posts.index', [
            'posts' => Post::with('author')->
            where('active', 1)->
            latest()->get()
        ]);
    }

    public function my_posts(Request $request): View
    {

        return view('posts.my_posts', [
            'posts' => $request->user()->posts
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $post = new Post();
        $post->title = $validated['title'];
        $post->body = $validated['body'];
        $post->author_id = $request->user()->id;
        $post->slug = Str::slug($post->title);
        $post->active = true;

        $post->save();

        return redirect(route('posts.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post): View
    {
        $this->authorize('update', $post);

        return view('posts.edit', [
            'post' => $post,
        ]);
    }

    public function archive(Post $post): RedirectResponse
    {
        $this->authorize('update', $post);

        $post->timestamps = false;
        $post->active = !$post->active;
        $post->save();

        return redirect(route('posts.index'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post): RedirectResponse
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'body' => 'required|string',
            'title' => 'required|string|max:255'
        ]);

        $post->update($validated);

        return redirect(route('posts.index'));
    }

    public function comment(Request $request, Post $post): RedirectResponse
    {
        $comment = new Comment();
        $comment->post_id = $post->id;
        $comment->user_id = $request->user()->id;
        $comment->body = $request->input('comment');
        $comment->save();

        return redirect(route('posts.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post): RedirectResponse
    {
        $this->authorize('delete', $post);

        $post->delete();

        return redirect(route('posts.index'));
    }
}
