<?php

namespace App\Jobs;

use App\Models\Provider;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class PermanentlyDeleteProvider implements ShouldQueue
{
    use Queueable;

    protected $providerId;

    public function __construct($providerId)
    {
        $this->providerId = $providerId;
    }

    public function handle(): void
    {
        $provider = Provider::withTrashed()->find($this->providerId);

        if ($provider) {
            $provider->user()->withTrashed()->forceDelete();
            $provider->forceDelete();
        }
    }
}