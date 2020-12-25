<?php

namespace Label84\MailViewer\Tests\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Label84\MailViewer\Models\MailViewerItem;
use Label84\MailViewer\Tests\TestCase;
use Ramsey\Uuid\Lazy\LazyUuidFromString;

class MailViewerItemTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_has_the_required_columns()
    {
        $this->assertTrue(
            Schema::hasColumns(with(new MailViewerItem())->getTable(), [
                'uuid',
                'event_type',
                'mailer',
                'headers',
                'recipients',
                'notification',
                'subject',
                'body',
                'sent_at',
        ]));
    }

    /** @test */
    public function it_will_set_an_uuid_when_created()
    {
        $item = factory(MailViewerItem::class)->create();

        $this->assertTrue(preg_match(LazyUuidFromString::VALID_REGEX, $item->uuid) === 1);
    }

    /** @test */
    public function it_orders_items_by_date_desc_by_default()
    {
        $this->assertEquals((new MailViewerItem())->getGlobalScope('order'), function (Builder $builder) {
            $builder->orderBy('sent_at', 'desc');
        });
    }
}
