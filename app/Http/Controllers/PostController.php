<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use Validator;
use App\Models\Post;

/**
 * @group Post API
 */
class PostController extends Controller
{
    /**
    * Read
    */
    public function index()
    {
        $posts = auth()->user()->posts;
 
        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }
 
    /**
    * Details
    */
    public function show($id)
    {
        $post = auth()->user()->posts()->find($id);
 
        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found'
            ], 400);
        }
 
        return response()->json([
            'success' => true,
            'data' => $post->toArray()
        ], 400);
    }
 
    /**
    * Create
    */
    public function store(PostRequest $request)
    {
        $post = new Post();
        $post->title = $request->title;
        $post->description = $request->description;
        $post->price = $request->price;

        if(auth()->user()->posts()->save($post)){
            return response()->json([
                'success' => true,
                'data' => $post->toArray()
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Post not added'
            ], 500);
        }
    }
 
    /**
    * Update
    */
    public function update(PostRequest $request, $id)
    {
        $post = auth()->user()->posts()->find($id);

        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found'
            ], 400);
        }

        $updated = $post->fill($request->all())->save();

        if ($updated){
            return response()->json([
                'success' => true,
                'data' => $post->toArray()
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Post can not be updated'
            ], 500);
        }   
    }
 
    /**
    * Delete
    */
    public function destroy($id)
    {
        $post = auth()->user()->posts()->find($id);
 
        if (!$post) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found'
            ], 400);
        }
 
        if ($post->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Post can not be deleted'
            ], 500);
        }
    }
}
