<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrashTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_soft_deletes_then_restores_post(): void
    {
        $post = Post::factory()->create();

        $this->actingAs($post->user)
            ->delete(route('posts.destroy', $post->id))
            ->assertRedirect(route('posts.mine'));
        $this->assertSoftDeleted('posts', ['id' => $post->id]);
        $this->get(route('blog'))->assertDontSee($post->title);

        $this->actingAs($post->user)
            ->post(route('posts.restore', $post->id))
            ->assertRedirect(route('posts.trash'));
        $this->assertNotSoftDeleted('posts', ['id' => $post->id]);
    }

    public function test_other_user_cannot_delete_restore_or_purge(): void
    {
        $post = Post::factory()->create();
        $other = User::factory()->create();

        $this->actingAs($other)
            ->delete(route('posts.destroy', $post->id))->assertForbidden();
        $post->delete();
        $this->actingAs($other)
            ->post(route('posts.restore', $post->id))->assertForbidden();
        $this->actingAs($other)
            ->delete(route('posts.force', $post->id))->assertForbidden();
        $this->assertSoftDeleted('posts', ['id' => $post->id]);
    }
}
