<?php

namespace App\Message;

class SendEmailMessage
{
    private string $to;

    private string $subject;

    private string $textContent;

    private string $htmlContent;

    public function __construct(string $to, string $subject, string $textContent, string $htmlContent)
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->textContent = $textContent;
        $this->htmlContent = $htmlContent;
    }

    public function getTo(): string
    {
        return $this->to;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getTextContent(): string
    {
        return $this->textContent;
    }

    public function getHtmlContent(): string
    {
        return $this->htmlContent;
    }
}
