<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subjectText;
    public $bodyContent;
    public $isHtml;
    public $attachments;

    public function __construct(string $subjectText, string $bodyContent, bool $isHtml = true, array $attachments = [])
    {
        $this->subjectText = $subjectText;
        $this->bodyContent = $bodyContent;
        $this->isHtml = $isHtml;
        $this->attachments = $attachments;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectText,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail.SendMail',
            with: [
                'body' => $this->bodyContent,
                'subject' => $this->subjectText,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return collect($this->attachments)->map(function ($file) {
            return Attachment::fromPath($file->getPathname())
                ->as($file->getClientOriginalName());
        })->toArray();
    }
}