<?php

namespace Tests\Feature;

use App\Mail\ResetPasswordMail;
use App\Models\PasswordResetToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();
    }

    public function test_user_can_request_verify_and_reset_password(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => Hash::make('old-password'),
            'email_verified_at' => now(),
        ]);

        $this->post('/forgot-password', [
            'email' => 'user@example.com',
        ])->assertRedirect(route('password.verify'))
          ->assertSessionHas('status');

        Mail::assertQueued(ResetPasswordMail::class);

        $record = PasswordResetToken::where('email', 'user@example.com')->first();

        $this->assertNotNull($record);
        $this->assertMatchesRegularExpression('/^\d{6}$/', $record->token);
        $this->assertFalse($record->verified);
        $this->assertSame(0, $record->attempts);

        $this->post('/password/verify', [
            'email' => 'user@example.com',
            'token' => $record->token,
        ])->assertRedirect(route('password.reset'));

        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'user@example.com',
            'verified' => true,
        ]);

        $this->post('/password/reset', [
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])->assertRedirect(route('login.show'));

        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => 'user@example.com',
        ]);
    }

    public function test_wrong_token_increments_attempts_and_reports_remaining_attempts(): void
    {
        User::factory()->create([
            'email' => 'user@example.com',
            'email_verified_at' => now(),
        ]);

        PasswordResetToken::create([
            'email' => 'user@example.com',
            'token' => '123456',
            'created_at' => now(),
            'expires_at' => now()->addMinutes(10),
        ]);

        $this->from('/password/verify')
            ->post('/password/verify', [
                'email' => 'user@example.com',
                'token' => '000000',
            ])
            ->assertRedirect('/password/verify')
            ->assertSessionHasErrors('token');

        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'user@example.com',
            'attempts' => 1,
        ]);
    }

    public function test_token_is_deleted_after_attempt_limit_is_reached(): void
    {
        PasswordResetToken::create([
            'email' => 'user@example.com',
            'token' => '123456',
            'created_at' => now(),
            'expires_at' => now()->addMinutes(10),
            'attempts' => 5,
        ]);

        $this->from('/password/verify')
            ->post('/password/verify', [
                'email' => 'user@example.com',
                'token' => '000000',
            ])
            ->assertRedirect('/password/verify')
            ->assertSessionHasErrors('token');

        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => 'user@example.com',
        ]);
    }

    public function test_expired_token_is_rejected_and_deleted(): void
    {
        PasswordResetToken::create([
            'email' => 'user@example.com',
            'token' => '123456',
            'created_at' => now()->subMinutes(20),
            'expires_at' => now()->subMinute(),
        ]);

        $this->from('/password/verify')
            ->post('/password/verify', [
                'email' => 'user@example.com',
                'token' => '123456',
            ])
            ->assertRedirect('/password/verify')
            ->assertSessionHasErrors('token');

        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => 'user@example.com',
        ]);
    }

    public function test_reset_form_requires_verified_session_and_database_flag(): void
    {
        PasswordResetToken::create([
            'email' => 'user@example.com',
            'token' => '123456',
            'created_at' => now(),
            'expires_at' => now()->addMinutes(10),
            'verified' => false,
        ]);

        $this->withSession([
            'password_reset_email' => 'user@example.com',
            'password_reset_verified_at' => now()->timestamp,
        ])->get('/password/reset')
            ->assertRedirect(route('password.request'))
            ->assertSessionHasErrors('email');
    }

    public function test_cleanup_command_removes_expired_password_reset_tokens(): void
    {
        PasswordResetToken::create([
            'email' => 'expired@example.com',
            'token' => '123456',
            'created_at' => now()->subMinutes(20),
            'expires_at' => now()->subMinute(),
        ]);

        PasswordResetToken::create([
            'email' => 'active@example.com',
            'token' => '654321',
            'created_at' => now(),
            'expires_at' => now()->addMinutes(10),
        ]);

        $this->artisan('password:clean-expired')
            ->expectsOutput('Deleted 1 expired token(s).')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('password_reset_tokens', ['email' => 'expired@example.com']);
        $this->assertDatabaseHas('password_reset_tokens', ['email' => 'active@example.com']);
    }

    public function test_unregistered_email_shows_error(): void
    {
        $this->post('/forgot-password', [
            'email' => 'nonexistent@example.com',
        ])->assertRedirect()
          ->assertSessionHasErrors(['email' => 'Email tidak terdaftar.']);

        Mail::assertNotQueued(ResetPasswordMail::class);
    }
}
