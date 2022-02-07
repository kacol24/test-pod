<?php

namespace App\Jobs;

use App\Models\Business\BusinessInstagram;
use App\Repositories\Facades\Instagram;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RefreshInstagramToken implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $threshold = today()->addDays(7);

        $needsRefresh = BusinessInstagram::whereDate('expired_at', '<=', $threshold)->get();
        Log::debug('REFRESH IG TOKEN: Found ' . $needsRefresh->count() . ' ' . Str::plural('account', $needsRefresh->count()) . ' that needs to be refreshed.');

        foreach ($needsRefresh as $businessInstagram) {
            Log::debug('Fetch token for ig_username ' . $businessInstagram->username . ' (business_id: '. $businessInstagram->business_id .')');
            $response = Instagram::refreshToken($businessInstagram->access_token);

            $businessInstagram->access_token = $response['access_token'];
            $businessInstagram->expired_at = Carbon::parse($businessInstagram->expired_in)
                                                   ->addSeconds($response['expires_in'])
                                                   ->format('Y-m-d');
            $businessInstagram->save();
            Log::debug('business_id: ' . $businessInstagram->business_id . ' token refreshed, expires in ' . $businessInstagram->expired_at);
        }
    }
}
