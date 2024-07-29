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
    public $subject;
    public function __construct()
    {

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        dd($quotation);
        return $this->view('quotation_email.emailproposal')
                    ->with([
                        'emailCom' => $this->emailCom,
                        'Quotation_ID' => $this->Quotation_ID,
                        'name' => $this->name,
                        'comtypefullname' => $this->comtypefullname,
                        'checkin' => $this->checkin,
                        'checkout' => $this->checkout,
                        'night' => $this->night,
                        'day' => $this->day,
                        'quotation' => $this->quotation,
                    ]);
    }
}
