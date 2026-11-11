<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScheduleTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_publishes_only_due_scheduled_posts(): void
    {
        $due = Post::factory()->scheduled(now()->subMinute())->create();
        $later = Post::factory()->scheduled(now()->addDay())->create();
        $draft = Post::factory()->draft()->create();

        $this->artisan('posts:publish-scheduled')->assertSuccessful();

        $this->assertSame('published', $due->fresh()->status);
        $this->assertSame('scheduled', $later->fresh()->status);
        $this->assertSame('draft', $draft->fresh()->status);
    }
}
