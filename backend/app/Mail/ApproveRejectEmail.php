<?php

namespace App\Mail;

use App\Models\Business\Business;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApproveRejectEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $business;

    public $reason;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($business, $reason)
    {
        $this->business = $business;
        $this->reason = $reason;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->business->status == Business::STATUS_APPROVED) {
            $subject = 'Selamat! '. $this->business->name .' telah tampil di JPCC Business Catalog';
        }

        if ($this->business->status == Business::STATUS_REJECTED) {
            $subject = 'Maaf, '. $this->business->name .' belum dapat tampil di JPCC Business Catalog';
        }
        return $this->subject($subject)
                    ->markdown('emails.approve_reject');
    }
}
