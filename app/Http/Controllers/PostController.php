<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Auth::user()->posts;
        return view('posts.index', compact('posts'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('posts.create');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|unique:posts|min:3|max:255',
            'summary' => 'max:2000',
            'body' => 'required',
            'published_at' => 'required|date',
        ]);


        $post = new Post();
        $post->user_id = Auth::id();
        $post->title = $request->title;
        $post->summary = $request->summary;
        $post->body = $request->body;
        $post->published_at = $request->published_at;
        $post->save();


        return redirect()->route('posts.index')
            ->with('success', 'Publicación creada correctamente');
    }


    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        echo "editando: " . $post;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if ($post->user_id == Auth::id()) {
            $post->delete();
            return redirect()->route('posts.index')
                ->with('success', 'Publicación eliminada correctamente.');
        } else {
            return redirect()->route('posts.index')
                ->with('error', 'No puedes eliminar una publicación de la que no eres el autor.');
        }
    }

    public function home()
    {
        $firstPosts = Post::select('id', 'title', 'summary', 'published_at', 'user_id')
            ->where('published_at', '<=', \Carbon\Carbon::today())
            ->orderByDesc('published_at')
            ->take(5)
            ->get();


        $otherPosts = Post::select('id', 'title', 'published_at', 'user_id')
            ->where('published_at', '<=', \Carbon\Carbon::today())
            ->orderByDesc('published_at')
            ->skip(5)
            ->take(20)
            ->get();

        return view('welcome', compact('firstPosts', 'otherPosts'));
    }

    public function read(int $id)
    {
        $post = Post::find($id);
        return view('posts.read', compact('post'));
    }

    public function vote(Post $post)
    {
        // Comprobamos que no haya votado ya.
        $vote = $post->votedUsers()->find(Auth::id());
        if (!$vote) {
            // Si no ha votado, lo añadimos.
            $post->votedUsers()->attach(Auth::id());
        } else {
            // Si ha votado, lo eliminamos.
            $post->votedUsers()->detach(Auth::id());
        }
        return redirect()->back();
    }
}
