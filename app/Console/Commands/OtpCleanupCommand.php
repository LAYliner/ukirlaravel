<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EmailVerification;

class OtpCleanupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'otp:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired OTP codes and expired lockouts from the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Delete expired OTP records that are NOT locked, or locked records where lockout has expired
        $deleted = EmailVerification::where(function ($query) {
            $query->where('expires_at', '<', now())
                  ->where('is_locked', false);
        })->orWhere(function ($query) {
            $query->where('is_locked', true)
                  ->where('locked_until', '<', now());
        })->delete();

        $this->info("Cleaned up {$deleted} expired OTP/lockout records.");
    }
}
