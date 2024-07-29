<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\master_document_email;
class QuotationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $emailCom;
    public $Quotation_ID;
    public $name;
    public $comtypefullname;
    public $checkin;
    public $checkout;
    public $night;
    public $day;
    public $quotation;

    /**
     * Create a new message instance.
     *
     * @return void
     *
     */
    public $Data;
    public $subject;
    public function __construct($Data,$subject)
    {
        $this->Data = $Data;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->view('quotation_email.emailproposal')
        ->subject($this->subject)
        ->with('Data', $this->Data);
    }
}
