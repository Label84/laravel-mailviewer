<?php

namespace Label84\MailViewer\Tests\Unit\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Label84\MailViewer\Tests\TestCase;

class InstallMailViewerTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_copies_the_config_file_upon_installation()
    {
        if (File::exists(config_path('mailviewer.php'))) {
            unlink(config_path('mailviewer.php'));
        }

        $this->assertFalse(File::exists(config_path('mailviewer.php')));

        Artisan::call('vendor:publish', [
            '--provider' => "Label84\MailViewer\MailViewerServiceProvider",
            '--tag' => 'config'
        ]);

        $this->assertTrue(File::exists(config_path('mailviewer.php')));
    }
}
