<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $customer;
    public $pdfPath;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($customer, $pdfPath)
    {
        $this->customer = $customer;
        $this->pdfPath = $pdfPath;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $todayDate = \Carbon\Carbon::now()->format('d-m-y');
        $filename = 'invoice_' . $this->customer->id . '_' . $todayDate . '.pdf';

        return $this->subject('Invoice #' . $this->customer->id . ' - UNIKAA CRM')
                    ->view('emails.invoice')
                    ->attach($this->pdfPath, [
                        'as' => $filename,
                        'mime' => 'application/pdf',
                    ]);
    }
}
