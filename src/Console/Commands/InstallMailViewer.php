<?php

namespace Label84\MailViewer\Console\Commands;

use Illuminate\Console\Command;

class InstallMailViewer extends Command
{
    protected $hidden = true;

    protected $signature = 'mailviewer:install';

    protected $description = 'Install the MailViewer package.';

    public function handle(): void
    {
        $this->info('Installing mail viewer package...');

        $this->call('vendor:publish', [
            '--provider' => "Label84\MailViewer\MailViewerServiceProvider",
            '--tag' => 'config'
        ]);

        $this->call('vendor:publish', [
            '--provider' => "Label84\MailViewer\MailViewerServiceProvider",
            '--tag' => 'migrations'
        ]);

        $this->info('All done!');
    }
}
