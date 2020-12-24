<?php

namespace Label84\MailViewer\Tests\Commands;

use Illuminate\Support\Facades\Artisan;
use Label84\MailViewer\Models\MailViewerItem;
use Label84\MailViewer\Tests\TestCase;

class CleanupMailViewerItemsTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_has_30_days_as_default_value()
    {
        $this->assertEquals(30, config('mailviewer.delete_items_older_than_days'));
    }

    /** @test */
    public function it_deletes_items_older_than_30_days_by_default()
    {
        collect(range(1, 60))->each(function (int $i) {
            MailViewerItem::newFactory()->create([
                'sent_at' => today()->subDays($i)->startOfDay(),
            ]);
        });

        $this->assertCount(60, MailViewerItem::all());

        Artisan::call('mailviewer:cleanup');

        $this->assertCount(30, MailViewerItem::all());
    }

    /** @test */
    public function it_deletes_correct_number_of_items_with_10_days_option()
    {
        collect(range(1, 60))->each(function (int $i) {
            MailViewerItem::newFactory()->create([
                'sent_at' => today()->subDays($i)->startOfDay(),
            ]);
        });

        $this->assertCount(60, MailViewerItem::all());

        Artisan::call('mailviewer:cleanup --days=10');

        $this->assertCount(10, MailViewerItem::all());
    }

    /** @test */
    public function it_deletes_only_items_older_than_the_given_number_days()
    {
        collect(range(1, 60))->each(function (int $i) {
            MailViewerItem::newFactory()->create([
                'sent_at' => today()->subDays($i)->startOfDay(),
            ]);
        });

        Artisan::call('mailviewer:cleanup --days=30');

        $this->assertCount(30, MailViewerItem::where('sent_at', '>=', today()->subDays(30))->get());
        $this->assertCount(0, MailViewerItem::where('sent_at', '<', today()->subDays(30))->get());
    }
}
