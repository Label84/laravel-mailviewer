<?php

namespace Label84\MailViewer\Console\Commands;

use Illuminate\Console\Command;
use Label84\MailViewer\Models\MailViewerItem;

class CleanupMailViewerItems extends Command
{
    protected $hidden = false;

    protected $signature = 'mailviewer:cleanup {--days= : Clean up items that are older than x days.}';

    protected $description = 'Clean up old records from the mail viewer table.';

    public function handle(): void
    {
        /** @var int $days */
        $days = $this->option('days') ?? config('mailviewer.delete_items_older_than_days');

        $this->info('Cleaning mail viewer items...');

        $deletedItems = MailViewerItem::query()
            ->where('sent_at', '<', today()->subDays($days))
            ->delete();

        $this->info("Deleted {$deletedItems} records from the mail viewer table.");

        $this->info('All done!');
    }
}
