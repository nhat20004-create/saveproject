<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Policies\CommentPolicy;
use PHPUnit\Framework\TestCase;

class CommentPolicyTest extends TestCase
{
    private function comment(): Comment
    {
        $comment = new Comment(['user_id' => 2]);
        $comment->setRelation('post', new Post(['user_id' => 3]));

        return $comment;
    }

    private function user(int $id, string $role = 'member'): User
    {
        $user = new User();
        $user->id = $id;
        $user->role = $role;

        return $user;
    }

    public function test_writer_post_author_and_admin_may_delete(): void
    {
        $policy = new CommentPolicy();

        $users = [$this->user(2), $this->user(3), $this->user(9, 'admin')];

        foreach ($users as $user) {
            $this->assertTrue($policy->delete($user, $this->comment()));
        }
    }

    public function test_unrelated_member_may_not_delete(): void
    {
        $policy = new CommentPolicy();

        $stranger = $this->user(7);

        $this->assertFalse($policy->delete($stranger, $this->comment()));
    }
}
