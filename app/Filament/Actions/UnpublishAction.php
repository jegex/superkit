<?php

namespace App\Filament\Actions;

use App\Enums\ContentStatus;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;

class UnpublishAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'unpublish';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Unpublish');
        $this->requiresConfirmation();
        $this->action(function ($record, $livewire) {
            $record->update([
                'status' => ContentStatus::DRAFT,
                'scheduled_at' => null,
            ]);

            Notification::make()
                ->title('Post unpublished')
                ->body('Post has been moved to drafts')
                ->success()
                ->send();

            $livewire->refreshFormData([
                'status',
                'scheduled_at',
            ]);
        });
        $this->icon(Heroicon::OutlinedArchiveBox);
        $this->color('danger');
        $this->visible(fn ($record) => $record->status === ContentStatus::PUBLISHED || $record->status === ContentStatus::PENDING);
    }
}
