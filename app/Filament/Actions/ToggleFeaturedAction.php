<?php

namespace App\Filament\Actions;

use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;

class ToggleFeaturedAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'toggle_featured';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(fn ($record) => $record->is_featured ? 'Remove Featured' : 'Mark as Featured');
        $this->action(function ($record, $livewire) {
            $newValue = ! $record->is_featured;

            $record->update([
                'is_featured' => $newValue,
            ]);

            $status = $newValue ? 'featured' : 'unfeatured';

            Notification::make()
                ->title("Post {$status}")
                ->success()
                ->send();

            $livewire->refreshFormData(['is_featured']);
        });
        $this->icon(fn ($record) => $record->is_featured ? Heroicon::XMark : Heroicon::Star);
        $this->color(fn ($record) => $record->is_featured ? 'info' : 'gray');
    }
}
