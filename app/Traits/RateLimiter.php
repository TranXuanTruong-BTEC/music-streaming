<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

trait RateLimiter
{
    protected function hitRateLimit($key, $maxAttempts = 100, $decayMinutes = 1)
    {
        $attempts = Cache::get($key, 0) + 1;
        Cache::put($key, $attempts, now()->addMinutes($decayMinutes));

        if ($attempts > $maxAttempts) {
            Log::warning("Rate limit exceeded for key: $key");
            sleep(60); // Wait for 1 minute if rate limit is exceeded
            return true;
        }

        return false;
    }
}