<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    private function payload(array $extra = []): array
    {
        return $extra + [
            'title' => 'Xu hướng công nghệ',
            'category_id' => Category::factory()->create()->id,
            'status' => 'published',
            'content' => 'Nội dung bài viết đủ dài để hợp lệ.',
            'image_file' => $this->makePngUpload('cover.png'),
        ];
    }

    public function test_member_creates_post_with_tags_and_image(): void
    {
        $user = User::factory()->create();
        $tags = Tag::factory()->count(2)->create();

        $this->actingAs($user)->post(route('posts.store'),
            $this->payload(['tags' => $tags->pluck('id')->all()]))
            ->assertRedirect(route('blog'));

        $post = Post::firstOrFail();
        $this->assertSame($user->id, $post->user_id);
        $this->assertCount(2, $post->tags);
        $this->assertSame('xu-huong-cong-nghe', $post->slug);
        $this->assertFileExists(public_path($post->file_path));
        @unlink(public_path($post->file_path));
    }

    public function test_invalid_image_and_missing_fields_are_rejected(): void
    {
        $user = User::factory()->create();
        $pdf = UploadedFile::fake()->create('a.pdf', 10, 'application/pdf');
        $big = UploadedFile::fake()->create('big.png', 3000, 'image/png');

        foreach ([$pdf, $big] as $file) {
            $this->actingAs($user)->post(route('posts.store'),
                $this->payload(['image_file' => $file]))
                ->assertSessionHasErrors('image_file');
        }
        $this->actingAs($user)->post(route('posts.store'), [])
            ->assertSessionHasErrors(['title', 'category_id', 'status']);
        $this->assertSame(0, Post::count());
    }

    public function test_owner_can_update_but_other_user_gets_403(): void
    {
        $post = Post::factory()->create(['title' => 'Bài gốc']);
        $data = [
            'title' => 'Bài đã sửa',
            'category_id' => $post->category_id,
            'status' => 'published',
            'content' => 'Nội dung đã được chỉnh sửa xong.',
        ];

        $this->actingAs(User::factory()->create())
            ->put(route('posts.update', $post->id), ['title' => ''])
            ->assertForbidden();
        $this->actingAs($post->user)
            ->put(route('posts.update', $post->id), $data)
            ->assertRedirect(route('blog'));

        $this->assertSame('Bài đã sửa', $post->fresh()->title);
    }

    public function test_home_shows_published_newest_first_and_pages(): void
    {
        Post::factory()->create([
            'title' => 'Bài cũ',
            'published_at' => now()->subDays(2),
        ]);
        Post::factory()->create(['title' => 'Bài mới']);
        Post::factory()->draft()->create(['title' => 'Bài nháp ẩn']);
        Post::factory()->scheduled()->create(['title' => 'Bài hẹn ẩn']);

        $this->get(route('blog'))->assertOk()
            ->assertSeeInOrder(['Bài mới', 'Bài cũ'])
            ->assertDontSee('Bài nháp ẩn')
            ->assertDontSee('Bài hẹn ẩn');

        Post::factory()->count(11)->create();
        $this->get(route('blog'))->assertSee('page=2', false);
    }

    public function test_detail_by_slug_and_404_for_unknown_or_draft(): void
    {
        $post = Post::factory()->create(['slug' => 'bai-co-slug']);
        $draft = Post::factory()->draft()->create(['slug' => 'bai-nhap']);

        $this->get('/posts/bai-co-slug')->assertOk()->assertSee($post->title);
        $this->get('/posts/khong-co')->assertNotFound();
        $this->get('/posts/bai-nhap')->assertNotFound();
        $this->actingAs($draft->user)->get('/posts/bai-nhap')->assertOk();
    }
}
