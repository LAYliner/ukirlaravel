<?php

namespace App\Console\Commands;

use App\Models\PasswordResetToken;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CleanExpiredPasswordResets extends Command
{
    protected $signature = 'password:clean-expired';

    protected $description = 'Clean up expired password reset tokens';

    public function handle(): int
    {
        $deleted = PasswordResetToken::where('expires_at', '<', now())->delete();

        Log::info("Cleaned up {$deleted} expired password reset token(s).");
        $this->info("Deleted {$deleted} expired token(s).");

        return self::SUCCESS;
    }
}
