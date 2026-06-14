<?php

namespace App\Filament\Actions;

use App\Enums\ContentStatus;
use Filament\Actions\Action;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;

class PublishAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'publish';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Publish Now');
        $this->schema([
            Toggle::make('notify_subscribers')
                ->label('Notify subscribers')
                ->helperText('Send an email notification to all blog subscribers')
                ->default(false),
        ]);
        $this->requiresConfirmation();
        $this->action(function ($record, $livewire) {
            $record->update([
                'status' => ContentStatus::PUBLISHED,
                'published_at' => now(),
                'last_published_at' => now(),
            ]);

            Notification::make()
                ->title('Post published successfully!')
                ->success()
                ->send();

            $livewire->refreshFormData([
                'status',
                'published_at',
                'last_published_at',
            ]);
        });
        $this->icon(Heroicon::OutlinedCheckCircle);
        $this->color('success');
        $this->visible(fn ($record) => auth()->user()->can('publish', $record) &&
            $record->status !== ContentStatus::PUBLISHED
        );
    }
}
