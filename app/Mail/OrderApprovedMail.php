<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order->load('items.product', 'user');
    }

    public function build()
    {
        return $this
            ->subject('🌿 Your Order is Confirmed – Thank you for shopping green!')
            ->view('emails.order-approved');
    }
}
