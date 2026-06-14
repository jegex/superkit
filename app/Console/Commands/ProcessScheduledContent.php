<?php

namespace App\Console\Commands;

use App\Services\ContentService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('content:process-scheduled')]
#[Description('Publish all scheduled content whose scheduled_at has passed')]
class ProcessScheduledContent extends Command
{
    public function handle(ContentService $content): void
    {
        $count = $content->processScheduledContent();

        $this->components->info("Published {$count} scheduled content(s).");
    }
}
