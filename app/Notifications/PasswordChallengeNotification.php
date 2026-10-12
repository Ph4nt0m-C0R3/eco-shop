<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Bus\Queueable;

class PasswordChallengeNotification extends Notification implements ShouldQueue, ShouldBeEncrypted
{
    use Queueable;
    public function __construct(public readonly string $code) { $this->afterCommit(); }
    public function via($notifiable): array { return ['mail']; }
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)->subject(config('app.brand').' — '.__('auth-security.password_code'))
            ->line(__('auth-security.code_message', ['code' => $this->code]))
            ->line(__('auth-security.code_expiry'));
    }
}
