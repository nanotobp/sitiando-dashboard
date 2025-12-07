<?php

namespace App\Mail;

use App\Models\AffiliatePayout;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AffiliatePayoutReady extends Mailable
{
    use Queueable, SerializesModels;

    public $payout;
    public $affiliate;

    public function __construct(AffiliatePayout $payout)
    {
        $this->payout = $payout;
        $this->affiliate = $payout->affiliate;
    }

    public function build()
    {
        return $this->subject('Tu liquidación está lista — Sitiando ')
            ->markdown('emails.payout_ready');
    }
}
