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
    public $Data;
    public $subject;
    protected $pdfPath;
    protected $filePaths ;
    protected $promotions ;
      /**
     * Create a new message instance.
     *
     * @param array $data
     * @param string $title
     * @param string $pdfPath
     * @param array $filePaths
     *  @param array $promotions
     * @return void
     */


    public function __construct($Data,$subject, $pdfPath,$filePaths,$promotions)
    {
        $this->Data = $Data;
        $this->subject = $subject;
        $this->pdfPath = $pdfPath;
        $this->filePaths  = $filePaths ;
        $this->promotions  = $promotions ;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $email=$this->view('quotation_email.emailproposal')
        ->subject($this->subject)
        ->with('Data', $this->Data)
        ->attach($this->pdfPath);
        foreach ($this->filePaths as $filePath) {
            $email->attach($filePath);
        }
        foreach ($this->promotions as $promotions) {
            $email->attach($promotions);
        }
        return $email->from('reservation@together-resort.com', 'Together Resort');
    }
}
