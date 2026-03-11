<?php

namespace App\Services;

use App\Models\SmsLog;
use Twilio\Rest\Client;

class SmsService
{
    public function send(string $phone, string $message, string $type = 'general'): array
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $from = config('services.twilio.from');

        if (!$sid || !$token || !$from) {
            $errorMessage = 'Twilio credentials are missing in configuration.';

            SmsLog::create([
                'phone' => $phone,
                'message' => $message,
                'type' => $type,
                'status' => 'failed',
                'response' => $errorMessage,
            ]);

            return [
                'success' => false,
                'message' => $errorMessage,
            ];
        }

        try {
            $client = new Client($sid, $token);

            $twilioMessage = $client->messages->create($phone, [
                'from' => $from,
                'body' => $message,
            ]);

            $response = [
                'success' => true,
                'sid' => $twilioMessage->sid ?? null,
                'status' => $twilioMessage->status ?? null,
                'to' => $phone,
                'from' => $from,
                'type' => $type,
            ];

            SmsLog::create([
                'phone' => $phone,
                'message' => $message,
                'type' => $type,
                'status' => 'sent',
                'response' => json_encode($response),
            ]);

            return $response;
        } catch (\Throwable $e) {
            SmsLog::create([
                'phone' => $phone,
                'message' => $message,
                'type' => $type,
                'status' => 'failed',
                'response' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'SMS sending failed.',
                'error' => $e->getMessage(),
            ];
        }
    }
}