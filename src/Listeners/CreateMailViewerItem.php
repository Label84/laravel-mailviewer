<?php

namespace Label84\MailViewer\Listeners;

use Illuminate\Mail\Events\MessageSent;
use Label84\MailViewer\Models\MailViewerItem;
use Symfony\Component\Mime\Email;

class CreateMailViewerItem
{
    public function handle(MessageSent $event): void
    {
        if (! $this->shouldLog($event)) {
            return;
        }

        MailViewerItem::create([
            'event_type' => MessageSent::class,
            'mailer' => config('mail.default') ?? '',
            'headers' => $this->formatHeaders($event->message->getHeaders()),
            'notification' => $event->data['__laravel_notification'] ?? null,
            'recipients' => $this->formatRecipients($event->message),
            'subject' => $event->message->getSubject(),
            'body' => $event->message->getBody(),
            'sent_at' => now(),
        ]);
    }

    /** @param mixed $headers */
    private function formatHeaders($header): array
    {
        if (! config('mailviewer.database.include.headers')) {
            return [];
        }

        return [
            'content-type' => $header->get('content-type')->getFieldBody(),
            'mime-version' => $header->get('mime-version')->getFieldBody(),
            'date' => $header->get('date')->getFieldBody(),
            'message-id' => $header->get('message-id')->getFieldBody(),
            'from' => $header->get('from')->getFieldBody(),
        ];
    }

    private function formatRecipients(Email $message): array
    {
        return array_merge(
            ['to' => array_keys($message->getTo() ?: [])],
            ['cc' => array_keys($message->getCc() ?: [])],
            ['bcc' => array_keys($message->getBcc() ?: [])],
        );
    }

    private function shouldLog(MessageSent $event): bool
    {
        /* Check package enabled */
        if (! config('mailviewer.enabled')) {
            return false;
        }

        /* Check exclude notification list */
        if (isset($event->data['__laravel_notification']) && in_array($event->data['__laravel_notification'], config('mailviewer.database.exclude.notification') ?? [])) {
            return false;
        }

        /* Check exclude email list */
        if ($event->message->getTo() && in_array(array_keys($event->message->getTo())[0], config('mailviewer.database.exclude.email') ?? [])) {
            return false;
        }

        return true;
    }
}
