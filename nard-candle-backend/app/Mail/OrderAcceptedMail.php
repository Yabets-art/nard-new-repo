<?php

// app/Mail/OrderAcceptedMail.php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\CustomOrder;

class OrderAcceptedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(CustomOrder $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this->view('emails.order-accepted')
                    ->with(['order' => $this->order]);
    }
}
