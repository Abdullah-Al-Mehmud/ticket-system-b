<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $subjectText;
    public string $bodyContent;
    public bool $isHtml;
    public array $mailAttachments;

    public function __construct(
        string $subjectText,
        string $bodyContent,
        bool $isHtml = true,
        array $attachments = []
    ) {
        $this->subjectText = $subjectText;
        $this->bodyContent = $bodyContent;
        $this->isHtml = $isHtml;
        $this->mailAttachments = $attachments;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectText ?: 'No Subject',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.SendMail',
            with: [
                'body' => $this->bodyContent,
                'subject' => $this->subjectText,
                'isHtml' => $this->isHtml,
            ]
        );
    }

    public function attachments(): array
    {
        return collect($this->mailAttachments)
            ->filter(function ($attachment) {
                return isset($attachment['data'], $attachment['name'], $attachment['mime']);
            })
            ->map(function ($attachment) {
                return Attachment::fromData(
                    fn() => $attachment['data'],
                    name: $attachment['name']
                )->withMime($attachment['mime']);
            })
            ->toArray();
    }
}