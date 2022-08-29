<?php

namespace Label84\MailViewer\Tests\Views;

use Carbon\Carbon;
use Label84\MailViewer\Models\MailViewerItem;
use Label84\MailViewer\Tests\TestCase;

class ViewTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_has_admin_mailviewer_as_default_route_prefix()
    {
        $this->assertEquals('/admin/mailviewer', config('mailviewer.route.prefix'));
    }

    /** @test */
    public function it_has_web_as_default_middleware()
    {
        $this->assertEquals(['web'], config('mailviewer.route.middleware'));
    }

    /** @test */
    public function it_does_not_have_auth_as_default_middleware()
    {
        $this->assertNotContains(['auth'], config('mailviewer.route.middleware'));
    }

    /** @test */
    public function it_can_list_the_mail_viewer_items()
    {
        $response = $this->withoutMiddleware()->get(route('mailviewer.index'));

        $response->assertSuccessful();

        $response->assertViewHas('mails', MailViewerItem::paginate(50));
    }

    /** @test */
    public function it_can_list_the_mail_viewer_items_with_to_cc_bcc_query_filter()
    {
        MailViewerItem::factory()->create([
            'recipients' => ['to' => ['test@example.com'], 'cc' => [], 'bcc' => []],
        ]);

        // TODO default testing database connection doesn't support JSON CONTAINS operation
        $response = $this->withoutMiddleware()->get(route('mailviewer.index', ['to_X' => 'test@example.com']));

        $response->assertSuccessful();

        $response->assertViewHas('mails');
    }

    /** @test */
    public function it_can_list_the_mail_viewer_items_with_notification_base_class_query_filter()
    {
        $class = class_basename(\Illuminate\Auth\Notifications\VerifyEmail::class);

        $response = $this->withoutMiddleware()->get(route('mailviewer.index', ['notification' => $class]));

        $response->assertSuccessful();

        $response->assertViewHas('mails', MailViewerItem::where('notification', '\Illuminate\Auth\Notifications\VerifyEmail')
            ->paginate(50)
            ->appends(['notification' => $class]));
    }

    /** @test */
    public function it_can_list_the_mail_viewer_items_with_from_query_filter()
    {
        collect(range(1, 50))->each(function (int $i) {
            MailViewerItem::factory()->create([
                'sent_at' => today()->subDays($i)->startOfDay(),
            ]);
        });

        $this->assertCount(50, MailViewerItem::all());

        $fromDate = today()->subDays(20)->format('Y-m-d');

        $response = $this->withoutMiddleware()->get(route('mailviewer.index', ['from' => $fromDate]));

        $response->assertSuccessful();

        $response->assertViewHas('mails', MailViewerItem::whereDate('sent_at', '>=', $fromDate)
            ->paginate(50)
            ->appends(['from' => $fromDate]));
    }

    /** @test */
    public function it_can_list_the_mail_viewer_items_with_till_query_filter()
    {
        collect(range(1, 50))->each(function (int $i) {
            MailViewerItem::factory()->create([
                'sent_at' => today()->subDays($i)->startOfDay(),
            ]);
        });

        $this->assertCount(50, MailViewerItem::all());

        $tillDate = today()->subDays(20)->format('Y-m-d');

        $response = $this->withoutMiddleware()->get(route('mailviewer.index', ['till' => $tillDate]));

        $response->assertSuccessful();

        $response->assertViewHas('mails', MailViewerItem::whereDate('sent_at', '<=', $tillDate)
            ->paginate(50)
            ->appends(['till' => $tillDate]));
    }

    /** @test */
    public function it_can_list_the_mail_viewer_items_with_around_query_filter()
    {
        collect(range(1, 50))->each(function (int $i) {
            MailViewerItem::factory()->create([
                'sent_at' => now()->subMinutes($i),
            ]);
        });

        $this->assertCount(50, MailViewerItem::all());

        $aroundTime = now()->format('Y-m-d H:i');

        $response = $this->withoutMiddleware()->get(route('mailviewer.index', ['around' => $aroundTime]));

        $response->assertSuccessful();

        $response->assertViewHas('mails', MailViewerItem::whereBetween('sent_at', [
                Carbon::createFromFormat('Y-m-d H:i', $aroundTime)->subMinutes(10),
                Carbon::createFromFormat('Y-m-d H:i', $aroundTime)->addMinutes(10),
            ])
            ->paginate(50)
            ->appends(['around' => $aroundTime]));
    }

    /** @test */
    public function it_can_list_the_mail_viewer_items_with_around_and_d_query_filter()
    {
        collect(range(1, 50))->each(function (int $i) {
            MailViewerItem::factory()->create([
                'sent_at' => now()->subMinutes($i),
            ]);
        });

        $this->assertCount(50, MailViewerItem::all());

        $aroundTime = now()->format('Y-m-d H:i');

        $response = $this->withoutMiddleware()->get(route('mailviewer.index', [
                'around' => $aroundTime,
                'd' => 5,
            ]));

        $response->assertSuccessful();

        $response->assertViewHas('mails', MailViewerItem::whereBetween('sent_at', [
                Carbon::createFromFormat('Y-m-d H:i', $aroundTime)->subMinutes(5),
                Carbon::createFromFormat('Y-m-d H:i', $aroundTime)->addMinutes(5),
            ])
            ->paginate(50)
            ->appends(['around' => $aroundTime, 'd' => 5]));
    }

    /** @test */
    public function it_has_pagination_when_item_count_is_more_than_item_per_page()
    {
        MailViewerItem::factory()->count(60)->create();

        $response = $this->withoutMiddleware()->get(route('mailviewer.index'));

        $response->assertSuccessful();

        $this->assertDatabaseCount('mail_viewer_items', 60);

        $response->assertViewHas('mails', MailViewerItem::paginate(50));
    }

    /** @test */
    public function it_can_show_the_mail_viewer_item_preview()
    {
        MailViewerItem::factory()->create();

        $response = $this->withoutMiddleware()->get(route('mailviewer.show', MailViewerItem::first()));

        $response->assertSuccessful();
    }

    /** @test */
    public function it_can_show_the_mail_viewer_analytics()
    {
        MailViewerItem::factory()->count(20)->create();

        $response = $this->withoutMiddleware()->get(route('mailviewer.analytics'));

        $response->assertSuccessful();

        $response->assertViewHas('items');
    }
}
