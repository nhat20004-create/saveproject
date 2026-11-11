<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Tự động đăng các bài hẹn giờ đã đến hạn (chạy: php artisan schedule:work)
Schedule::command('posts:publish-scheduled')->everyMinute();
