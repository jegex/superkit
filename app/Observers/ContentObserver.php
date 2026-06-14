<?php

namespace App\Observers;

use App\Enums\ContentStatus;
use App\Models\Content;
use App\Models\User;

class ContentObserver
{
    public function creating(Content $content): void
    {
        $user = auth()->user();

        if (! $user) {
            return;
        }

        if (empty($content->created_by)) {
            $content->created_by = $user->id;
        }

        $content->updated_by = $user->id;

        if ($user->hasRole('author') && empty($content->author_id)) {
            $content->author_id = $user->id;
        }

        if (empty($content->author_id)) {
            $firstAuthor = User::whereHas('roles', function ($query) {
                $query->where('name', 'author');
            })->first();

            if ($firstAuthor) {
                $content->author_id = $firstAuthor->id;
            }
        }

        if ($user->hasRole('author')) {
            $content->is_featured = false;

            if ($content->status === ContentStatus::PUBLISHED) {
                $content->status = ContentStatus::DRAFT;
            }
        }
    }

    public function updating(Content $content): void
    {
        $user = auth()->user();

        if (! $user) {
            return;
        }

        $content->updated_by = $user->id;

        if ($user->hasRole('author')) {
            $original = $content->getOriginal();

            if ($content->isDirty('is_featured')) {
                $content->is_featured = $original['is_featured'] ?? false;
            }

            if ($content->isDirty('status') && $content->status === ContentStatus::PUBLISHED) {
                $content->status = $original['status'] ?? ContentStatus::DRAFT;
            }

            if ($content->isDirty('author_id') && $original['author_id'] !== $user->id) {
                $content->author_id = $original['author_id'];
            }

            if ($content->isDirty('created_by') && $original['created_by'] !== $user->id) {
                $content->created_by = $original['created_by'];
            }
        }
    }

    public function created(Content $content): void
    {
        if ($content->status === ContentStatus::PENDING) {
            // TODO: Notify editors/admins about pending content
        }
    }

    public function updated(Content $content): void
    {
        if ($content->wasChanged('status')) {
            // TODO: Handle status change notifications
        }
    }
}
