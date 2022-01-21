<?php

namespace Label84\MailViewer\Tests\Config;

use Illuminate\Support\Facades\Notification;
use Label84\MailViewer\Models\MailViewerItem;
use Label84\MailViewer\Tests\Events\Notifications\TestNotification;
use Label84\MailViewer\Tests\TestCase;

class ConfigTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_does_not_an_item_when_disabled()
    {
        config()->set('mailviewer.enabled', false);

        Notification::route('mail', 'info@example.com')->notify(new TestNotification());

        $this->assertCount(0, MailViewerItem::all());
    }

    /** @test */
    public function it_does_not_create_an_item_when_notification_is_in_exclude_notification_list()
    {
        config()->set('mailviewer.database.exclude.notification', [
            TestNotification::class,
        ]);

        Notification::route('mail', 'info@example.com')->notify(new TestNotification());

        $this->assertCount(0, MailViewerItem::all());
    }

    /** @test */
    public function it_does_not_create_an_item_when_email_is_in_exclude_email_list()
    {
        config()->set('mailviewer.database.exclude.email', [
            'info@example.com',
        ]);

        Notification::route('mail', 'info@example.com')->notify(new TestNotification());

        $this->assertCount(0, MailViewerItem::all());
    }
}
