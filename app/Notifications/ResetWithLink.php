<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetWithLink extends ResetPassword implements ShouldQueue
{
    use Queueable;

    /**
     * Create a notification instance.
     *
     * @param string $token
     * @param string $routeName
     * @return void
     */
    public function __construct(string $token, public string $routeName = 'password.reset')
    {
        parent::__construct($token);
    }

    /**
     * Get the reset URL for the given notifiable.
     *
     * @param mixed $notifiable
     * @return string
     */
    protected function resetUrl(mixed $notifiable): string
    {
        return url(route(
            name: $this->routeName,
            parameters: [
                'token' => $this->token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ],
            absolute: false,
        ));
    }
}
