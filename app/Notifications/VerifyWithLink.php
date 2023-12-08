<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class VerifyWithLink extends VerifyEmail implements ShouldQueue
{
    use Queueable;

    /**
     * Create a notification instance.
     *
     * @param string $routeName
     * @return void
     */
    public function __construct(public string $routeName = 'verification.verify')
    {
        //
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param mixed $notifiable
     * @return string
     */
    protected function verificationUrl(mixed $notifiable): string
    {
        return URL::temporarySignedRoute(
            name: $this->routeName,
            expiration: Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            parameters: [
                $notifiable->getKeyName() => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ],
        );
    }
}
