<?php

namespace Label84\MailViewer\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Label84\MailViewer\MailViewerServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    protected function getPackageProviders($app): array
    {
        return [
            MailViewerServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        config()->set('mailviewer.database_connection', 'sqlite');
        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);
    }

    protected function setUpDatabase()
    {
        Schema::create(config('mailviewer.table_name'), function (Blueprint $table) {
            $table->uuid('uuid')->primary();

            $table->string('event_type');
            $table->string('mailer');

            $table->text('headers')->nullable();
            $table->text('recipients');

            $table->string('notification')->nullable();

            $table->string('subject')->nullable();
            $table->text('body')->nullable();

            $table->string('sent_at');
        });
    }
}
