<?php

namespace App\Console\Commands;

use App\Models\Ad;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DeleteExpiredAds extends Command
{
    protected $signature = 'ads:delete-expired';

    protected $description = 'Delete ads that are older than 5 days';

    public function handle()
    {
        $expiredAds = Ad::where('created_at', '<=', now()->subDays(5))->get();

        $count = 0;

        foreach ($expiredAds as $ad) {
            if ($ad->image && Storage::disk('public')->exists($ad->image)) {
                Storage::disk('public')->delete($ad->image);
            }
            $ad->delete();
            $count++;
        }

        $this->info("Deleted {$count} expired ad(s).");
    }
}
