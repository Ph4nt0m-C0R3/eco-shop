<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Bus\Queueable;

class CustomerResetPasswordNotification extends ResetPassword implements ShouldQueue, ShouldBeEncrypted
{
    use Queueable;
    public function __construct($token) { parent::__construct($token); $this->afterCommit(); }
    public function toMail($notifiable)
    {
        return parent::toMail($notifiable)->subject(config('app.brand').' — '.__('auth-security.reset_password_subject'));
    }
}
