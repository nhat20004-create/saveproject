<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->admin()->create();
    }

    public function test_admin_opens_dashboard_with_statistics(): void
    {
        $top = Post::factory()->create(['title' => 'Bài nhiều bình luận']);
        Comment::factory()->count(3)->create(['post_id' => $top->id]);

        $this->actingAs($this->admin())->get('/admin')
            ->assertOk()
            ->assertSee('Bài nhiều bình luận')
            ->assertSee('Thành viên');
    }

    public function test_guest_and_member_get_403_on_admin_area(): void
    {
        $this->get('/admin')->assertForbidden();
        $this->get('/admin/categories')->assertForbidden();

        $this->actingAs(User::factory()->create())
            ->get('/admin')->assertForbidden();
    }
}
