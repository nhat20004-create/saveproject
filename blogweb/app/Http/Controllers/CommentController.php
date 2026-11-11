<?php

namespace App\Http\Controllers;

use App\Mail\NewCommentMail;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        abort_unless($post->isPublished(), 404);

        $data = $request->validate(
            ['body' => ['required', 'string', 'max:1000']],
            [
                'body.required' => 'Vui lòng nhập nội dung bình luận.',
                'body.max' => 'Bình luận tối đa 1000 ký tự.',
            ]
        );

        $comment = $post->comments()->create([
            'user_id' => Auth::id(),
            'body' => $data['body'],
        ]);

        // Lỗi gửi mail không được làm mất bình luận vừa lưu.
        try {
            Mail::to($post->user)->queue(new NewCommentMail($comment));
        } catch (Throwable $e) {
            Log::error('Gửi mail bình luận thất bại', [
                'comment_id' => $comment->id,
                'error' => $e->getMessage(),
            ]);
        }

        return redirect()->route('posts.show', $post->slug)
            ->with('success', 'Đã gửi bình luận.');
    }

    public function destroy(Comment $comment)
    {
        Gate::authorize('delete', $comment);
        $comment->delete();

        Log::info('comment_deleted', [
            'comment_id' => $comment->id,
            'by' => Auth::id(),
        ]);

        return back()->with('success', 'Đã xóa bình luận.');
    }
}
