<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $token; // Define a property to store the token

    /**
     * Create a new notification instance.
     *
     * @param string $token The password reset token
     */
    public function __construct($token)
    {
        $this->token = $token; // Store the token passed to the constructor
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $url = url('/reset-password/' . $this->token . '?email=' . urlencode($notifiable->getEmailForPasswordReset()));

        return (new MailMessage)
            ->subject('Ресетирање на лозинка')
            ->greeting('Здраво!')
            ->line('Ви испративме мејл за да ја ресетирате вашата лозинка!')
            ->action('Ресетирајте лозинка овде', $url)
            ->line('Овој линк ќе истече за 60 минути од испраќањето на мејлот.')
            ->line('Ако не сте испратиле линк за ресетирање, не правите ништо повеќе.')
            ->salutation('Со почит, Игралиште.')
            ->line('Ако имате проблем со кликање на копчето, копирајте го овој URL во нов таб на вашиот пребарувач:    ' . $url);
    }


    
    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
