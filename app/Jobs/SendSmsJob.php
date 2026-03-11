<?php

namespace App\Jobs;

use App\Services\SmsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendSmsJob implements ShouldQueue
{
    use Queueable;

    public string $phone;
    public string $message;
    public string $type;

    public function __construct(string $phone, string $message, string $type = 'general')
    {
        $this->phone = $phone;
        $this->message = $message;
        $this->type = $type;
    }

    public function handle(SmsService $smsService): void
    {
        $smsService->send($this->phone, $this->message, $this->type);
    }
}