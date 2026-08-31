<?php

namespace App\Console\Commands;

use App\Models\PendingRegistration;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CleanupExpiredPendingRegistrations extends Command
{
    protected $signature = 'cleanup:pending-registrations';

    protected $description = 'Delete pending registrations whose OTP has expired without successful verification';

    public function handle(): int
    {
        $deletedCount = PendingRegistration::where('otp_expires_at', '<', Carbon::now())->delete();

        $this->info("Deleted {$deletedCount} expired pending registration(s).");

        return self::SUCCESS;
    }
}
