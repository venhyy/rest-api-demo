<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PostController extends Controller
{

    public function all()
    {
        $posts = Post::orderBy("created_at", "desc")->get(["title", "content", "id", "user_id"]);
        $posts_appended = array();

        foreach ($posts as $post) {
            $user = User::find($post->user_id);
            $post->user = [
                "name" => $user->name,
                "email" => $user->email
            ];
            array_push($posts_appended, $post);
        }

        return response()->json([
            "posts" => $posts_appended
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Auth::id();
        $posts = Post::where("user_id", $id)->orderBy("title", "asc")->get(["title", "content", "id"]);

        return response()->json([
            "posts" => $posts
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    /* public function create(Request $request)
    {
    } */
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "title" => "required",
            "content" => "required"
        ]);

        $id = Auth::id();

        Post::create([
            "title" => $request->title,
            "content" => $request->content,
            "user_id" => $id
        ]);

        return response()->json([
            "message" => "Post added"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        $posts = Post::where("id", $post->id)->get(["title", "content", "id", "user_id"]);

        return response()->json([
            "post" => $posts
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    /*  public function edit(Post $post)
    {
        //
    } */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $request->validate([
            "title" => "required",
            "content" => "required"
        ]);

        if (Auth::id() == $post->user_id) {
            $post->update($request->all());
            return response()->json(["message" => "Post updated."]);
        }

        return response()->json(["message" => "Unauthorized"], 401);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {

        if (Auth::id() == $post->user_id) {
            $post->delete();
            return response()->json(["message" => "Post deleted."]);
        }

        return response()->json(["message" => "Unauthorized"], 401);
    }
}
