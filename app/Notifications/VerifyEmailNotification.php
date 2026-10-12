<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerifyEmailNotification extends VerifyEmail implements ShouldQueue, ShouldBeEncrypted
{
    use Queueable;

    public function __construct() { $this->afterCommit(); }

    public function toMail($notifiable)
    {
        return parent::toMail($notifiable)->subject(config('app.brand').' — '.__('auth-security.verify_email_subject'));
    }
}
