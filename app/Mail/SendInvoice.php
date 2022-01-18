<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendInvoice extends Mailable
{
    use Queueable, SerializesModels;

    public $pdf;
    public $company;
    public $type;
    public $invoiceType;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($pdf, $company, $type)
    {
        $this->pdf = $pdf;
        $this->type = $type;
        $this->company = $company;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->type == 'sell') {
          $this->invoiceType = 'Faktura';
        } elseif ($this->type == 'creditNote') {
          $this->invoiceType = 'Kreditnota';
        } elseif ($this->type == 'reminder') {
          $this->invoiceType = 'Rykker';
        }

        return $this->view('emails.sendInvoice')
          ->subject($this->invoiceType." fra ".$this->company->title)
          ->attachFromStorage($this->pdf);
    }
}
