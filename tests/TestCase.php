<?php

namespace Label84\MailViewer\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Label84\MailViewer\MailViewerServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    protected function getPackageProviders($app): array
    {
        return [
            MailViewerServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        include_once __DIR__.'/../database/migrations/create_mail_viewer_items_table.php.stub';

        (new \CreateMailViewerItemsTable())->up();
    }
}
