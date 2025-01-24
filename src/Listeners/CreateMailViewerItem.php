<?php

namespace Label84\MailViewer\Listeners;

use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
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
            'body' => config('mailviewer.database.include.body', true) ? $message->getHtmlBody() : null,
            'sent_at' => Carbon::now()->timezone(config('app.timezone', 'UTC')),
        ]);
    }

    private function formatHeaders(Headers $headers): ?array
    {
        if (! config('mailviewer.database.include.headers')) {
            return null;
        }

        return [
            'date' => $headers->get('date'),
            'message-id' => $headers->get('message-id')?->getIds(),
            'from' => $headers->get('from'),
        ];
    }

    private function formatRecipients(Email $message): array
    {
        return array_merge(
            ['to' => (new Collection($message->getTo()))->map(fn ($address) => $address->getAddress())->toArray()],
            ['cc' => (new Collection($message->getCc()))->map(fn ($address) => $address->getAddress())->toArray()],
            ['bcc' => (new Collection($message->getBcc()))->map(fn ($address) => $address->getAddress())->toArray()],
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

        /* Make sure recipient is not in list of excluded email addresses */
        if ($event->message->getTo() && count((new Collection($event->message->getTo()))
            ->map(fn ($address) => $address->getAddress())
            ->reject(fn ($email) => in_array($email, config('mailviewer.database.exclude.email') ?? []))) == 0) {
            return false;
        }

        return true;
    }
}
