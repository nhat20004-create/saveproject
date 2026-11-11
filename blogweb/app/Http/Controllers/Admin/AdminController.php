<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard', [
            'users' => User::count(),
            'posts' => Post::published()->count(),
            'comments' => Comment::count(),
            'top' => Post::published()
                ->withCount('comments')
                ->orderByDesc('comments_count')
                ->take(5)
                ->get(),
        ]);
    }

    public function posts()
    {
        $posts = Post::withTrashed()->with(['user', 'category'])
            ->latest()->paginate(15);

        return view('admin.posts', ['posts' => $posts]);
    }

    public function destroyPost(Post $post)
    {
        if ($post->file_path && ! str_starts_with($post->file_path, 'http')) {
            @unlink(public_path($post->file_path));
        }

        $post->forceDelete();
        Log::info('post_deleted_by_admin', [
            'post_id' => $post->id,
            'by' => Auth::id(),
        ]);

        return back()->with('success', 'Đã xóa bài viết vi phạm.');
    }

    public function comments()
    {
        $comments = Comment::with(['user', 'post'])->latest()->paginate(15);

        return view('admin.comments', ['comments' => $comments]);
    }
}
