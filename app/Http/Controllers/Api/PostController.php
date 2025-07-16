<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Traits\ApiResponse;

class PostController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::latest()->paginate(10);

        if ($posts->isEmpty()) {
            return $this->successResponse([], 'لا توجد منشورات');
        }

        return $this->successResponse(
            PostResource::collection($posts)->response()->getData(true),
            'Posts retrieved successfully',
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts_images', 'public');
        }
        $post = auth()->user()->posts()->create($data);
        if (!$post) {
            return $this->errorResponse([], 'حدث خطأ أثناء إنشاء المنشور', 500);
        }
        return $this->successResponse(
            new PostResource($post),
            'Post created successfully',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = auth()->user()->posts()->find($id);
        if (!$post) {
            return $this->errorResponse([], 'Post not found', 404);
        }
        return $this->successResponse(
            new PostResource($post),
            'Post retrieved successfully',
            200
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostRequest $request, $id)
    {
        $post = auth()->user()->posts()->find($id);
        if (!$post) {
            return $this->errorResponse([], 'Post not found', 404);
        }
        $data = $request->validated();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 'public');
        }
        $post->update($data);
        return $this->successResponse(
            new PostResource($post),
            'Post updated successfully',
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = auth()->user()->posts()->find($id);
        if (!$post) {
            return $this->errorResponse([], 'Post not found', 404);
        }
        $post->delete();
        return $this->successResponse(
            [],
            'Post deleted successfully',
            200
        );
    }

}
