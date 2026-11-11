<?php

namespace Tests\Feature;

use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    private string $authLog;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authLog = storage_path('logs/auth-test.log');
        @unlink($this->authLog);
        config(['logging.channels.auth' => [
            'driver' => 'single',
            'path' => $this->authLog,
            'level' => 'info',
        ]]);
    }

    protected function tearDown(): void
    {
        @unlink($this->authLog);
        parent::tearDown();
    }

    private function logContents(): string
    {
        return is_file($this->authLog)
            ? file_get_contents($this->authLog)
            : '';
    }

    private function hasPassword(User $user, string $plain): bool
    {
        return Hash::check($plain, $user->fresh()->password);
    }

    private function registerData(array $extra = []): array
    {
        return $extra + [
            'name' => 'Sinh Vien',
            'email' => 'sv@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];
    }

    public function test_login_succeeds_with_correct_password(): void
    {
        $user = User::factory()->create(['password' => 'password123']);

        $this->post('/login', [
            'email' => $user->email, 'password' => 'password123',
        ])->assertRedirect(route('blog'));

        $this->assertAuthenticatedAs($user);
        $this->assertStringContainsString('login', $this->logContents());
    }

    public function test_login_fails_with_wrong_password_and_writes_log(): void
    {
        $user = User::factory()->create(['password' => 'password123']);

        $this->post('/login', ['email' => $user->email, 'password' => 'sai'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
        $this->assertStringContainsString('login_failed', $this->logContents());
    }
}
