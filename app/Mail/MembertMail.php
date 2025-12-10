<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MembertMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $contactData;

    public function __construct($contactData)
    {
        $this->contactData = $contactData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
       public function build()
    {
        return $this->from('picknow.ecommerce@gmail.com', 'Unikaa Beauty')
                    ->subject('Your Membership Card - Unikaa Beauty')
                    ->view('emails.member_email')
                    ->with([
                        'name' => $this->contactData['name'] ?? '',
                        'membership_card' => $this->contactData['membership_card'] ?? '',
                    ]);
    }
}
