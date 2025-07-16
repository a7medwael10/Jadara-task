<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class StatController extends Controller
{
    use ApiResponse;

    public function getStats()
    {
        $usersCount = User::count();
        $postsCount = Post::count();
        $usersWithoutPostsCount = User::doesntHave('posts')->count();

        return $this->successResponse(
            [
                'users_count' => $usersCount,
                'posts_count' => $postsCount,
                'users_without_posts_count' => $usersWithoutPostsCount,
            ],
            'Statistics retrieved successfully',
            200
        );
    }
}
