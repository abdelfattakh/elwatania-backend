<?php

namespace App\Notifications;

use App\Notifications\Channels\WhatsappChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\Exceptions\CouldNotSendNotification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\AndroidConfig;
use NotificationChannels\Fcm\Resources\AndroidFcmOptions;
use NotificationChannels\Fcm\Resources\AndroidNotification;
use NotificationChannels\Fcm\Resources\ApnsConfig;
use NotificationChannels\Fcm\Resources\ApnsFcmOptions;

class GeneralNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public string  $title,
        public string  $body,
        public ?string $image = null,
        public array   $methods = ['database', 'fcm', 'whatsapp', 'mail'],
    )
    {
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
        $channels = [];

        if (in_array('database', $this->methods)) {
            $channels[] = 'database';
        }

        if (in_array('mail', $this->methods)) {
            $channels[] = 'mail';
        }

        if (in_array('fcm', $this->methods)) {
            $channels[] = FcmChannel::class;
        }

        if (in_array('whatsapp', $this->methods)) {
            $channels[] = WhatsappChannel::class;
        }

        return $channels;
    }

    /**
     * Prepare FCM Message.
     *
     * @param mixed $notifiable
     * @return mixed
     * @throws CouldNotSendNotification
     */
    public function toFcm(mixed $notifiable): FcmMessage
    {
        return FcmMessage::create()
            ->setData([
                'title' => $this->title,
                'body' => $this->body,
                'image' => $this->image ?? asset('logo-dark.png'),
            ])
            ->setNotification(
                \NotificationChannels\Fcm\Resources\Notification::create()
                    ->setTitle($this->title)
                    ->setBody($this->body)
                    ->setImage($this->image ?? asset('logo-dark.png'))
            )
            ->setAndroid(
                AndroidConfig::create()
                    ->setFcmOptions(AndroidFcmOptions::create()->setAnalyticsLabel('analytics'))
                    ->setNotification(AndroidNotification::create()->setColor('#0A0A0A'))
            )->setApns(
                ApnsConfig::create()
                    ->setFcmOptions(ApnsFcmOptions::create()->setAnalyticsLabel('analytics_ios')),
            );
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(config('app.name') . ': ' . $this->title)
            ->line($this->body)
            ->line('Thank you for using !' . config('app.name'));
    }

    /**
     * Get Whatsapp Data to be Sent (phones/message).
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toWhatsapp(mixed $notifiable): array
    {
        $phones = [];

        if (method_exists($notifiable, 'getPhoneForVerification')) {
            $phones[] = $notifiable->getPhoneForVerification();
        }

        return [
            'phones' => $phones,
            'type' => $this->image ? 'IMAGE' : 'TEXT',
            'image' => $this->image,
            'message' => "{$this->title}\n" .
                "{$this->body}\n" .
                "--------------------------\n" .
                config('app.name'),
        ];
    }

    /**
     * Get the Database representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toDatabase(mixed $notifiable): array
    {
        return array_merge(
            \Filament\Notifications\Notification::make()
                ->title($this->title)
                ->body($this->body)
                ->getDatabaseMessage(),
            [
                'image' => $this->image ?? asset('logo-dark.png'),
            ]
        );
    }
}
