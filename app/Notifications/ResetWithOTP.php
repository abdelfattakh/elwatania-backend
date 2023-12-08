<?php

namespace App\Notifications;

use App\Models\OTP;
use App\Notifications\Channels\WhatsappChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetWithOTP extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(
        /**
         * OTP Instance.
         * @var OTP $otp
         */
        public OTP $otp,
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via(mixed $notifiable): array
    {
        if ($this->otp->isSentToMail()) {
            return ['mail'];
        }

        return [WhatsappChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail(mixed $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject(config('app.name') . ': Reset password Email')
            ->line("This is your Reset code for " . $this->otp->getAttribute('target'))
            ->action($this->otp->getAttribute('code'), '#' . $this->otp->getAttribute('code'))
            ->line('Thank you for using ' . config('app.name'));
    }

    /**
     * Get Whatsapp Data to be Sent (phones/message).
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toWhatsapp(mixed $notifiable): array
    {
        return [
            'phones' => [$this->otp->getAttribute('target')],
            'type' => 'TEXT',
            'message' => "your otp is {$this->otp->getAttribute('code')}\n" .
                "Expires At: {$this->otp->getAttribute('expires_at')->locale('en_US')->isoFormat('Y/M/D hh:mm A')}\n" .
                "رمز التحقق الخاص بكم هو : {$this->otp->getAttribute('code')}\n" .
                "ينتهي في: {$this->otp->getAttribute('expires_at')->locale('ar_SA')->isoFormat('Y/M/D hh:mm A')}\n" .
                "--------------------------\n" . config('app.name'),
        ];
    }
}
