<?php

namespace Tests\Unit;

use App\Models\Post;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    public function test_owner_is_recognized(): void
    {
        $post = new Post(['user_id' => 5]);

        $this->assertTrue($post->isOwnedBy(5));
        $this->assertFalse($post->isOwnedBy(9));
        $this->assertFalse($post->isOwnedBy(null));
    }

    public function test_only_published_status_counts_as_published(): void
    {
        $make = fn ($status) => new Post(['status' => $status]);

        $this->assertTrue($make(Post::PUBLISHED)->isPublished());
        $this->assertFalse($make(Post::DRAFT)->isPublished());
        $this->assertFalse($make(Post::SCHEDULED)->isPublished());
    }
}
