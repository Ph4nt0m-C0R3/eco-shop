<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class DeliveryTestMail extends Mailable
{
    public function build()
    {
        return $this->subject(config('app.brand').' — '.__('auth-security.email_test_subject'))->view('emails.delivery-test');
    }
}
