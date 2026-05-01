<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ActivateAccountNotification extends Notification
{
    use Queueable;

    public $token;
    public $businessName;

    public function __construct(string $token, string $businessName)
    {
        $this->token = $token;
        $this->businessName = $businessName;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $url = url(route('activate', ['token' => $this->token], false));

        return (new MailMessage)
            ->subject('Activate your '. config('app.name') .' account')
            ->greeting('Welcome, ' . $notifiable->name . '!')
            ->line('Thanks for registering **' . $this->businessName . '** on ' . config('app.name') . '.')
            ->line('Please confirm your email address to activate your business account.')
            ->action('Activate My Account', $url)
            ->line('If you did not create this account, you can safely ignore this email.');
    }
}
