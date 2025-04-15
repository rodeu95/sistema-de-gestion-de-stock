<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotificacionVencimiento extends Mailable
{
    use Queueable, SerializesModels;

    public $productos;
    /**
     * Create a new message instance.
     */
    public function __construct($productos)
    {
        $this->productos = $productos;
    }

    public function build()
    {
        return $this->subject('Productos Próximos a Vencer')
                    ->markdown('emails.productos_vencimiento')
                    ->with('productos', $this->productos);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Productos próximos a vencerse',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.productos_vencimiento',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
