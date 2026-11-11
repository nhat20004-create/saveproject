<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('partials.navbar', function ($view) {
            $view->with('navCategories', Category::orderBy('name')->get());
        });

        ResetPassword::toMailUsing(function ($notifiable, string $token) {
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            $broker = config('auth.defaults.passwords');
            $minutes = config("auth.passwords.{$broker}.expire");

            return (new MailMessage)
                ->subject('Đặt lại mật khẩu - Góc Nhìn 24h')
                ->greeting('Xin chào '.$notifiable->name.',')
                ->line('Chúng tôi nhận được yêu cầu cấp lại mật khẩu '
                    .'cho tài khoản của bạn.')
                ->action('Đặt lại mật khẩu', $url)
                ->line("Liên kết có hiệu lực trong {$minutes} phút.")
                ->line('Nếu bạn không yêu cầu, hãy bỏ qua email này, '
                    .'mật khẩu của bạn sẽ không thay đổi.');
        });
    }
}
