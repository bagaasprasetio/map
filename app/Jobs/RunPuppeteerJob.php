<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class RunPuppeteerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        // $scriptPath = base_path('resources/js/pup-parent.cjs'); // Path ke Puppeteer script
        $scriptPath = base_path('resources/js/google.cjs'); // Path ke Puppeteer script

        $email      = 'rikalikal97@gmail.com';
        $pin        = '232323';
        $inputTrx   = 100;
        $type       = 'UM'; // atau RT
        $URL        = config('app.url_verification_nik');

        $cleanedData = ['1234567890123456', '6543210987654321']; // Contoh NIK
        $jsonNikList = escapeshellarg(json_encode(array_values($cleanedData)));

        // Build command string
        $command = "node $scriptPath $email $pin $jsonNikList $type $URL $inputTrx 2>&1";

        // Timestamp sebelum eksekusi
        $startTime = now()->toDateTimeString();

        // Eksekusi Node script
        $output = shell_exec($command);

        // Timestamp sesudah eksekusi
        $endTime = now()->toDateTimeString();

        // Logging
        Log::info("=== Puppeteer Script Execution ===");
        Log::info("Start Time: {$startTime}");
        Log::info("Command: {$command}");
        Log::info("Output:\n" . $output);
        Log::info("End Time: {$endTime}");
    }
}
