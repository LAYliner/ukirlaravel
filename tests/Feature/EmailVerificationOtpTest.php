<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\EmailVerification;
use App\Mail\OtpMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class EmailVerificationOtpTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    public function test_user_can_register_and_receives_otp()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '08123456789',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'user',
        ]);

        $response->assertRedirect('/verify-otp');
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'email_verified_at' => null,
            'role' => 'user',
        ]);

        $this->assertDatabaseHas('email_verifications', [
            'email' => 'test@example.com',
            'attempts' => 0,
            'is_locked' => false,
        ]);

        Mail::assertSent(OtpMail::class);
        $this->assertEquals('test@example.com', session('pending_verification_email'));
    }

    public function test_user_can_verify_otp_successfully()
    {
        // Setup pending registration in session & database
        $user = User::create([
            'id' => 'user-uuid-1',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'is_active' => true,
        ]);

        $otpPlain = '123456';
        EmailVerification::create([
            'email' => 'test@example.com',
            'otp_code' => hash('sha256', $otpPlain),
            'attempts' => 0,
            'is_locked' => false,
            'expires_at' => now()->addMinutes(10),
        ]);

        $response = $this->withSession(['pending_verification_email' => 'test@example.com'])
            ->post('/verify-otp', [
                'otp' => $otpPlain,
            ]);

        $response->assertRedirect('/verification-success');
        
        $this->assertNull(session('pending_verification_email'));
        $this->assertTrue(session('verification_completed'));

        $this->assertNotNull($user->fresh()->email_verified_at);
        $this->assertDatabaseMissing('email_verifications', [
            'email' => 'test@example.com',
        ]);
    }

    public function test_otp_verification_fails_with_wrong_code()
    {
        $user = User::create([
            'id' => 'user-uuid-1',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'is_active' => true,
        ]);

        EmailVerification::create([
            'email' => 'test@example.com',
            'otp_code' => hash('sha256', '123456'),
            'attempts' => 0,
            'is_locked' => false,
            'expires_at' => now()->addMinutes(10),
        ]);

        $response = $this->withSession(['pending_verification_email' => 'test@example.com'])
            ->from('/verify-otp')
            ->post('/verify-otp', [
                'otp' => '000000',
            ]);

        $response->assertRedirect('/verify-otp');
        $response->assertSessionHasErrors('otp');

        $this->assertDatabaseHas('email_verifications', [
            'email' => 'test@example.com',
            'attempts' => 1,
            'is_locked' => false,
        ]);
        $this->assertNull($user->fresh()->email_verified_at);
    }

    public function test_otp_lockout_after_five_failed_attempts()
    {
        $user = User::create([
            'id' => 'user-uuid-1',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'is_active' => true,
        ]);

        EmailVerification::create([
            'email' => 'test@example.com',
            'otp_code' => hash('sha256', '123456'),
            'attempts' => 4,
            'is_locked' => false,
            'expires_at' => now()->addMinutes(10),
        ]);

        $response = $this->withSession(['pending_verification_email' => 'test@example.com'])
            ->from('/verify-otp')
            ->post('/verify-otp', [
                'otp' => '000000',
            ]);

        $response->assertRedirect('/verify-otp');
        $response->assertSessionHasErrors('otp');

        $this->assertDatabaseHas('email_verifications', [
            'email' => 'test@example.com',
            'attempts' => 5,
            'is_locked' => true,
        ]);
    }

    public function test_unverified_user_cannot_login()
    {
        $user = User::create([
            'id' => 'user-uuid-1',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'is_active' => true,
            'email_verified_at' => null,
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_inactive_user_cannot_login()
    {
        $user = User::create([
            'id' => 'user-uuid-1',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'is_active' => false,
            'email_verified_at' => now(),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_verified_active_user_can_login()
    {
        $user = User::create([
            'id' => 'user-uuid-1',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    public function test_otp_cleanup_command_removes_expired_records()
    {
        // 1. Expired, unlocked record (should be deleted)
        EmailVerification::create([
            'email' => 'expired-unlocked@example.com',
            'otp_code' => hash('sha256', '111111'),
            'attempts' => 0,
            'is_locked' => false,
            'expires_at' => now()->subMinutes(1),
        ]);

        // 2. Active, unlocked record (should NOT be deleted)
        EmailVerification::create([
            'email' => 'active-unlocked@example.com',
            'otp_code' => hash('sha256', '222222'),
            'attempts' => 0,
            'is_locked' => false,
            'expires_at' => now()->addMinutes(10),
        ]);

        // 3. Locked, lockout period active (should NOT be deleted)
        EmailVerification::create([
            'email' => 'locked-active@example.com',
            'otp_code' => hash('sha256', '333333'),
            'attempts' => 5,
            'is_locked' => true,
            'expires_at' => now()->subMinutes(20), // OTP expired but locked
            'locked_until' => now()->addHours(1),
        ]);

        // 4. Locked, lockout period expired (should be deleted)
        EmailVerification::create([
            'email' => 'locked-expired@example.com',
            'otp_code' => hash('sha256', '444444'),
            'attempts' => 5,
            'is_locked' => true,
            'expires_at' => now()->subMinutes(20),
            'locked_until' => now()->subMinutes(1),
        ]);

        $this->artisan('otp:cleanup')
            ->expectsOutput('Cleaned up 2 expired OTP/lockout records.')
            ->assertExitCode(0);

        $this->assertDatabaseMissing('email_verifications', ['email' => 'expired-unlocked@example.com']);
        $this->assertDatabaseHas('email_verifications', ['email' => 'active-unlocked@example.com']);
        $this->assertDatabaseHas('email_verifications', ['email' => 'locked-active@example.com']);
        $this->assertDatabaseMissing('email_verifications', ['email' => 'locked-expired@example.com']);
    }
}
