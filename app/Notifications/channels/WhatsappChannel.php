<?php

namespace App\Notifications\Channels;

use App\Notifications\VerifyWithOTP;
use Exception;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;

class WhatsappChannel
{
    /**
     * Send the given notification.
     *
     * @param mixed $notifiable
     * @param Notification|VerifyWithOTP $notification
     * @return void
     */
    public function send(mixed $notifiable, Notification|VerifyWithOTP $notification): void
    {
        $message = $notification->toWhatsapp($notifiable);

        foreach (array_filter($message['phones']) as $phone) {
            $this->sendMessage($phone, $message);
        }
    }

    /**
     * Send message to whatsapp
     *
     * @param string $phone
     * @param array $message
     * @return bool
     */
    public function sendMessage(string $phone, array $message): bool
    {
        try {
            if (config('app.debug')) {
                info($phone);
                info(json_encode($message));
            }

            Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . config('aqwhatsapp.api_token'),
                'Accept' => 'application/json',
            ])
                ->timeout(5)
                ->throw()
                ->post('https://cloudwa.net/api/v2/messages/send-message', [
                    'session_uuid' => config('aqwhatsapp.session_uuid'),
                    'phone' => $this->normalizeNumber($phone),
                    'message' => $message['message'],
                    'schedule_at' => now(),
                    'type' => $message['type'] ?? 'TEXT',
                    'image' => $message['image'] ?? null,
                ]);
            return true;
        } catch (Exception $e) {
            if (config('app.debug')) {
                info($e->getMessage());
                info($phone);
                info(json_encode($message));
            }
            unset($e);

            return false;
        }
    }

    /**
     * Filtering Out un-needed from the number
     *
     * @param string $phone
     * @return string
     */
    private function normalizeNumber(string $phone): string
    {
        // Remove All Non-Digits
        $phone = preg_replace('/\D/', '', $phone);

        // Remove All Spaces
        $phone = preg_replace('/\s/', '', $phone);

        // Remove All Starting Zeros
        return ltrim($phone, '0');
    }
}
