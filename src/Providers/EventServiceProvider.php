<?php

namespace Label84\MailViewer\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Mail\Events\MessageSent;
use Label84\MailViewer\Listeners\CreateMailViewerItem;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        MessageSent::class => [
            CreateMailViewerItem::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}
