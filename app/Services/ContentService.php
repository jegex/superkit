<?php

namespace App\Services;

use App\Enums\ContentStatus;
use App\Models\Content;

class ContentService
{
    public function processScheduledContent(): int
    {
        return Content::query()
            ->scheduled()
            ->update([
                'status' => ContentStatus::PUBLISHED,
                'published_at' => now(),
                'last_published_at' => now(),
                'scheduled_at' => null,
            ]);
    }
}
