<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'q' => trim((string) $request->query('q', '')),
            'category' => $request->query('category'),
            'tag' => $request->query('tag'),
        ];

        $posts = Post::published()
            ->filter($filters)
            ->with(['user', 'category', 'tags'])
            ->withCount('comments')
            ->latest('published_at')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('blog', [
            'posts' => $posts,
            'filters' => $filters,
            'categories' => Category::withCount([
                'posts' => fn ($q) => $q->published(),
            ])->orderBy('name')->get(),
            'tags' => Tag::orderBy('name')->get(),
        ]);
    }

    public function show(Post $post)
    {
        if (! $post->isPublished()) {
            abort_unless($post->isOwnedBy(Auth::id()), 404);
        }

        $post->load(['user', 'category', 'tags', 'comments.user']);

        return view('post_show', ['post' => $post]);
    }

    public function mine()
    {
        $posts = Post::where('user_id', Auth::id())
            ->with('category')->latest()->paginate(10);

        return view('post_mine', ['posts' => $posts, 'trashed' => false]);
    }

    public function trash()
    {
        $posts = Post::onlyTrashed()->where('user_id', Auth::id())
            ->with('category')->latest('deleted_at')->paginate(10);

        return view('post_mine', ['posts' => $posts, 'trashed' => true]);
    }

    public function create()
    {
        return view('post_create', $this->formData());
    }

    public function store(StorePostRequest $request)
    {
        $post = Post::create($this->fields($request) + [
            'user_id' => Auth::id(),
            'slug' => Post::uniqueSlug($request->title),
            'file_path' => $this->storeImage($request->file('image_file')),
        ]);
        $post->tags()->sync($request->input('tags', []));

        return $this->afterSave($post, 'Đã tạo bài viết.');
    }

    public function edit(Post $post)
    {
        Gate::authorize('update', $post);

        return view('post_edit', $this->formData() + ['post' => $post]);
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $post->fill($this->fields($request, $post));

        if ($request->hasFile('image_file')) {
            $this->deleteImage($post->file_path);
            $post->file_path = $this->storeImage($request->file('image_file'));
        }

        $post->save();
        $post->tags()->sync($request->input('tags', []));

        return $this->afterSave($post, 'Đã cập nhật bài viết.');
    }

    public function destroy(Post $post)
    {
        Gate::authorize('delete', $post);
        $post->delete();
        Log::info('post_deleted', ['post_id' => $post->id, 'by' => Auth::id()]);

        return redirect()->route('posts.mine')
            ->with('success', 'Đã chuyển bài viết vào thùng rác.');
    }

    public function restore(Post $post)
    {
        Gate::authorize('restore', $post);
        $post->restore();

        return redirect()->route('posts.trash')
            ->with('success', 'Đã khôi phục bài viết.');
    }

    public function forceDelete(Post $post)
    {
        Gate::authorize('forceDelete', $post);
        $this->deleteImage($post->file_path);
        $post->forceDelete();
        Log::info('post_purged', ['post_id' => $post->id, 'by' => Auth::id()]);

        return redirect()->route('posts.trash')
            ->with('success', 'Đã xóa vĩnh viễn bài viết.');
    }

    private function formData(): array
    {
        return [
            'categories' => Category::orderBy('name')->get(),
            'tags' => Tag::orderBy('name')->get(),
        ];
    }

    private function fields(Request $request, ?Post $post = null): array
    {
        $status = $request->input('status');

        $publishedAt = match ($status) {
            Post::SCHEDULED => $request->date('published_at'),
            Post::PUBLISHED => $post?->isPublished()
                ? $post->published_at
                : now(),
            default => null,
        };

        return [
            'title' => $request->title,
            'category_id' => $request->category_id,
            'content' => $request->input('content'),
            'status' => $status,
            'published_at' => $publishedAt,
        ];
    }

    private function afterSave(Post $post, string $message)
    {
        $route = $post->isPublished()
            ? redirect()->route('blog')
            : redirect()->route('posts.mine');

        return $route->with('success', $message);
    }

    private function storeImage(UploadedFile $file): string
    {
        $fileName = $file->hashName();
        $file->move(public_path('upload'), $fileName);

        return 'upload/'.$fileName;
    }

    private function deleteImage(?string $path): void
    {
        if (! $path || str_starts_with($path, 'http')) {
            return;
        }

        $fullPath = public_path($path);
        if (is_file($fullPath)) {
            unlink($fullPath);
        }
    }
}
