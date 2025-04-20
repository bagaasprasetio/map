<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email;

    public function __construct($email)
    {
        $this->email = $email;
    }

    public function handle()
    {
        // Simulasi proses: Kirim email (di sini kita cuma log)
        Log::info("Mengirim email ke: " . $this->email);
        sleep(3); // Simulasi proses yang memakan waktu
    }
}
