<?php

namespace App\Jobs;

use App\Mail\BusinessStatistics;
use App\Models\Business\Business;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class BusinessStatisticBlast implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $businesses = Business::approved()
                              ->get()
                              ->filter(function ($business) {
                                  return $business->created_at->diffInDays(today()) == 90;
                              });

        $startDate = today()->subDay()->subDays(90);
        $endDate = today()->subDay();

        foreach ($businesses as $business) {
            Mail::to($business->email)
                ->queue(new BusinessStatistics($business, $startDate, $endDate));
        }
    }
}
