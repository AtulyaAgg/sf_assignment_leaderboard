<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class GenerateQrCodeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle()
    {
        $qrApiUrl = "https://api.qrserver.com/v1/create-qr-code/";
        $response = Http::get($qrApiUrl, [
            'size' => '200x200',
            'data' => $this->user->address,
        ]);

        if ($response->successful()) {
            $qrFileName = "qrcodes/{$this->user->id}.png";
            Storage::disk('public')->put($qrFileName, $response->body());
            $this->user->update(['qr_code' => $qrFileName]);
        }
    }
}

