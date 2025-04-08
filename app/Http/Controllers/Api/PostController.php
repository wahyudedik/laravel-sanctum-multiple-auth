<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of posts based on user role.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        if ($user->hasRole('user')) {
            // User can only see their own posts
            $posts = Post::where('user_id', $user->id)
                ->with('user:id,name,email,role')
                ->latest()
                ->paginate(10);
        } else {
            // Dev and Admin can see all user posts
            $posts = Post::whereHas('user', function ($query) {
                $query->where('role', 'user');
            })
            ->with('user:id,name,email,role')
            ->latest()
            ->paginate(10);
        }

        return response()->json([
            'posts' => $posts,
        ]);
    }

    /**
     * Store a newly created post.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post = Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => $request->user()->id,
        ]);

        return response()->json([
            'message' => 'Post created successfully',
            'post' => $post,
        ], 201);
    }

    /**
     * Display the specified post.
     */
    public function show(Request $request, Post $post)
    {
        $user = $request->user();
        
        // Check if user has permission to view this post
        if ($user->hasRole('user') && $post->user_id !== $user->id) {
            return response()->json([
                'message' => 'You do not have permission to view this post',
            ], 403);
        }

        if (($user->hasRole('dev') || $user->hasRole('admin')) && 
            $post->user->role !== 'user') {
            return response()->json([
                'message' => 'You can only view posts from users with role "user"',
            ], 403);
        }

        return response()->json([
            'post' => $post->load('user:id,name,email,role'),
        ]);
    }

    /**
     * Update the specified post.
     */
    public function update(Request $request, Post $post)
    {
        // Only post owner can update their post
        if ($post->user_id !== $request->user()->id) {
            return response()->json([
                'message' => 'You do not have permission to update this post',
            ], 403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $post->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return response()->json([
            'message' => 'Post updated successfully',
            'post' => $post,
        ]);
    }

    /**
     * Remove the specified post.
     */
    public function destroy(Request $request, Post $post)
    {
        $user = $request->user();
        
        // Post owner, admin, and dev can delete posts
        if ($user->hasRole('user') && $post->user_id !== $user->id) {
            return response()->json([
                'message' => 'You do not have permission to delete this post',
            ], 403);
        }

        if (($user->hasRole('dev') || $user->hasRole('admin')) && 
            $post->user->role !== 'user') {
            return response()->json([
                'message' => 'You can only delete posts from users with role "user"',
            ], 403);
        }

        $post->delete();

        return response()->json([
            'message' => 'Post deleted successfully',
        ]);
    }
}
