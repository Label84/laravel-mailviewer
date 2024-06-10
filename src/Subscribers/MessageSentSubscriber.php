<?php

namespace Label84\MailViewer\Subscribers;

use Illuminate\Events\Dispatcher;
use Illuminate\Mail\Events\MessageSent;
use Label84\MailViewer\Listeners\CreateMailViewerItem;

class MessageSentSubscriber
{
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(MessageSent::class, [CreateMailViewerItem::class, 'handle']);
    }
}
