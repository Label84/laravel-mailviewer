<?php

namespace Label84\MailViewer\Tests\Unit\Config;

use Illuminate\Support\Facades\Notification;
use Label84\MailViewer\Models\MailViewerItem;
use Label84\MailViewer\Tests\TestCase;
use Label84\MailViewer\Tests\Unit\Events\Notifications\TestNotification;

class ConfigTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_wont_create_mail_viewer_items_when_set_disabled()
    {
        config()->set('mailviewer.enabled', false);

        Notification::route('mail', 'info@example.com')->notify(new TestNotification());

        $this->assertCount(0, MailViewerItem::all());
    }

    /** @test */
    public function it_wont_create_mail_viewer_items_when_notification_in_exclude_notification_list()
    {
        config()->set('mailviewer.database.exclude.notification', [
            TestNotification::class,
        ]);

        Notification::route('mail', 'info@example.com')->notify(new TestNotification());

        $this->assertCount(0, MailViewerItem::all());
    }

    /** @test */
    public function it_wont_create_mail_viewer_items_when_email_in_exclude_email_list()
    {
        config()->set('mailviewer.database.exclude.email', [
            'info@example.com',
        ]);

        Notification::route('mail', 'info@example.com')->notify(new TestNotification());

        $this->assertCount(0, MailViewerItem::all());
    }
}
