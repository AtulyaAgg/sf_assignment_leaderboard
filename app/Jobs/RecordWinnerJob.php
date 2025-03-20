<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Winner;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class RecordWinnerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        // Get the user(s) with the highest points
        $topUsers = User::orderByDesc('points')->limit(2)->get();

        // Ensure there's a single winner
        if ($topUsers->count() === 1) {
            $winner = $topUsers->first();

            // Store winner entry
            Winner::create([
                'user_id' => $winner->id,
                'points' => $winner->points,
                'timestamp' => Carbon::now(),
            ]);
        }
    }
}


