<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class InvoiceEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user       = $this->data['user'];
        $client     = $this->data['invoice']->client;
        $invoice_id = $this->data['invoice_id'];
        $pdf        = $this->data['pdf'];

        // $pdf = Storage::url($invoice->download_url);


        // $pdf        = public_path('storage/invoices/'.$invoice->download_url);

        return $this->markdown('emails.invoice', ['client' => $client])
            ->subject($invoice_id)
            ->from('gazimoshiul@gmail.com', $user->name)
            ->replyTo($user->email, $user->name)
            ->attach($pdf, ['mime' => 'application/pdf']);
    }
}
