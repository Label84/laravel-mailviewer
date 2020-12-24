<?php

namespace Label84\MailViewer\Tests\Events;

use Illuminate\Support\Facades\Notification;
use Label84\MailViewer\Models\MailViewerItem;
use Label84\MailViewer\Tests\Events\Notifications\TestNotification;
use Label84\MailViewer\Tests\TestCase;

class ListensOnMailEventsTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_triggers_the_listener_on_message_sent_event()
    {
        Notification::route('mail', 'info@example.com')->notify(new TestNotification());

        $this->assertCount(1, MailViewerItem::all());
    }
}
