<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Bus\Queueable;

class AdminResetPasswordNotification extends Notification implements ShouldQueue, ShouldBeEncrypted
{
    use Queueable;
    /**
     * The password reset token.
     */
    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
        $this->afterCommit();
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $url = route('admin.password.reset', [
            'token' => $this->token,
            'email' => $notifiable->email,
        ]);

        return (new MailMessage)
            ->subject(config('app.brand').' — '.__('auth-security.reset_password_subject'))
            ->line('Click the button below to reset your admin password.')
            ->action('Reset Password', $url)
            ->line('This link will expire in 15 minutes.');
    }
}
