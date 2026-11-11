<?php

namespace Tests\Feature;

use App\Mail\NewCommentMail;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_member_comments_and_author_gets_queued_mail(): void
    {
        Mail::fake();
        $post = Post::factory()->create();
        $member = User::factory()->create();

        $this->actingAs($member)
            ->post(route('comments.store', $post->slug), ['body' => 'Hay quá!'])
            ->assertRedirect(route('posts.show', $post->slug));

        $this->assertDatabaseHas('comments', [
            'post_id' => $post->id,
            'user_id' => $member->id,
            'body' => 'Hay quá!',
        ]);
        Mail::assertQueued(NewCommentMail::class,
            fn ($mail) => $mail->hasTo($post->user->email));
    }

    public function test_guest_empty_or_draft_comment_is_not_saved(): void
    {
        $post = Post::factory()->create();
        $draft = Post::factory()->draft()->create();
        $member = User::factory()->create();

        $this->post(route('comments.store', $post->slug), ['body' => 'x'])
            ->assertRedirect(route('login'));
        $this->actingAs($member)
            ->post(route('comments.store', $post->slug), ['body' => ''])
            ->assertSessionHasErrors('body');
        $this->actingAs($member)
            ->post(route('comments.store', $draft->slug), ['body' => 'x'])
            ->assertNotFound();

        $this->assertSame(0, Comment::count());
    }

    public function test_other_member_gets_403_deleting_comment(): void
    {
        $comment = Comment::factory()->create();

        $this->actingAs(User::factory()->create())
            ->delete(route('comments.destroy', $comment->id))
            ->assertForbidden();

        $this->assertDatabaseHas('comments', ['id' => $comment->id]);
    }
}
