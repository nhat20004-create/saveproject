<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PublishScheduledPosts extends Command
{
    protected $signature = 'posts:publish-scheduled';

    protected $description = 'Đăng các bài hẹn giờ đã đến hạn';

    public function handle(): int
    {
        $count = Post::where('status', Post::SCHEDULED)
            ->where('published_at', '<=', now())
            ->update(['status' => Post::PUBLISHED]);

        Log::info('scheduled_posts_published', ['count' => $count]);
        $this->info("Đã đăng {$count} bài hẹn giờ.");

        return self::SUCCESS;
    }
}
