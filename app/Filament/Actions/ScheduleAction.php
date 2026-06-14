<?php

namespace App\Filament\Actions;

use App\Enums\ContentStatus;
use Filament\Actions\Action;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;

class ScheduleAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'schedule';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Schedule Publication');
        $this->schema([
            DateTimePicker::make('scheduled_at')
                ->label('Publication Date & Time')
                ->seconds(false)
                ->required()
                ->default(now()->addDay()->startOfHour()),

            Toggle::make('notify_subscribers')
                ->label('Notify subscribers when published')
                ->helperText('Send an email notification to all blog subscribers')
                ->default(false),
        ]);
        $this->action(function ($record, $livewire, $data) {
            $record->update([
                'status' => ContentStatus::PENDING,
                'scheduled_at' => $data['scheduled_at'],
            ]);

            Notification::make()
                ->title('Post scheduled for publication')
                ->body('It will be automatically published on '.$data['scheduled_at']
                    ->timezone(auth()->user()->timezone)
                    ->format('M d, Y \a\t h:i A')
                )
                ->success()
                ->send();

            $livewire->refreshFormData([
                'status',
                'scheduled_at',
            ]);
        });
        $this->icon(Heroicon::Clock);
        $this->color('warning');
        $this->visible(fn ($record) => $record->status !== ContentStatus::PUBLISHED);
    }
}
