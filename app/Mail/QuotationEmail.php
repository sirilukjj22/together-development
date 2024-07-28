<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

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
    public function __construct($emailCom, $Quotation_ID, $name, $comtypefullname, $checkin, $checkout, $night, $day, $quotation)
    {
        $this->emailCom = $emailCom;
        $this->Quotation_ID = $Quotation_ID;
        $this->name = $name;
        $this->comtypefullname = $comtypefullname;
        $this->checkin = $checkin;
        $this->checkout = $checkout;
        $this->night = $night;
        $this->day = $day;
        $this->quotation = $quotation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('quotation_email.index')
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
