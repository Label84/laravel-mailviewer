<?php

namespace Label84\MailViewer\Listeners;

use Illuminate\Mail\Events\MessageSent;
use Label84\MailViewer\Models\MailViewerItem;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Header\Headers;

class CreateMailViewerItem
{
    public function handle(MessageSent $event): void
    {
        if (! $this->shouldLog($event)) {
            return;
        }

        /** @var \Symfony\Component\Mime\Email $message */
        $message = $event->message;

        MailViewerItem::create([
            'event_type' => MessageSent::class,
            'mailer' => config('mail.default') ?? '',
            'headers' => $this->formatHeaders($message->getHeaders()),
            'notification' => $event->data['__laravel_notification'] ?? null,
            'recipients' => $this->formatRecipients($message),
            'subject' => $message->getSubject(),
            'body' => $message->getBody(),
            'sent_at' => now(),
        ]);
    }

    private function formatHeaders(Headers $headers): array
    {
        if (! config('mailviewer.database.include.headers')) {
            return [];
        }

        return [
            'date' => $headers->get('date'),
            'message-id' => $headers->get('message-id'),
            'from' => $headers->get('from'),
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
        /* Make sure package is enabled */
        if (! config('mailviewer.enabled')) {
            return false;
        }

        /* Make sure notification is not in list of excluded notifications */
        if (isset($event->data['__laravel_notification']) && in_array($event->data['__laravel_notification'], config('mailviewer.database.exclude.notification') ?? [])) {
            return false;
        }

        /* Make sure recipient is not in list of exlcuded email addresses */
        if ($event->message->getTo() && in_array(array_keys($event->message->getTo())[0], config('mailviewer.database.exclude.email') ?? [])) {
            return false;
        }

        return true;
    }
}
