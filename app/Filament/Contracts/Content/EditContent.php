<?php

namespace App\Filament\Contracts\Content;

use App\Enums\ContentStatus;
use App\Filament\Actions\PublishAction;
use App\Filament\Actions\ScheduleAction;
use App\Filament\Actions\ToggleFeaturedAction;
use App\Filament\Actions\UnpublishAction;
use App\Filament\Concerns\HasLocaleSwitcher;
use App\Models\Content;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use LaraZeus\SpatieTranslatable\Resources\Pages\EditRecord\Concerns\Translatable;
use Relaticle\ActivityLog\Filament\Actions\ActivityLogAction;

class EditContent extends EditRecord
{
    use HasLocaleSwitcher;
    use Translatable;

    protected function getHeaderActions(): array
    {
        return [
            ...$this->getLocaleSwitcherActions(),
            ActionGroup::make([
                $this->buildPublishAction(),
                ScheduleAction::make(),
                $this->buildApproveAction(),
                UnpublishAction::make(),
                ToggleFeaturedAction::make(),
                ActivityLogAction::make()
                    ->label('Audit log')
                    ->icon('heroicon-o-shield-check')
                    ->visible(fn (): bool => auth()->user()?->isSuperAdmin() ?? false),
                DeleteAction::make()
                    ->label('Move to Trash')
                    ->successNotificationTitle('Post moved to trash')
                    ->visible(fn (Content $record): bool => ! $record->trashed()),
                ForceDeleteAction::make()
                    ->label('Permanent Delete')
                    ->modalDescription('This action cannot be undone. This will permanently delete the post from the server.')
                    ->visible(fn (Content $record): bool => $record->trashed()),
                RestoreAction::make()
                    ->label('Restore from Trash')
                    ->successNotificationTitle('Post restored from trash')
                    ->visible(fn (Content $record): bool => $record->trashed()),
            ]),
        ];
    }

    private function buildPublishAction(): PublishAction
    {
        return PublishAction::make()
            ->visible(fn (Content $record): bool => auth()->user()->can('publish', $record)
                && $record->status !== ContentStatus::PUBLISHED
                && ! $record->trashed()
            );
    }

    private function buildApproveAction(): Action
    {
        return Action::make('approve')
            ->label('Approve')
            ->icon('heroicon-o-check')
            ->color('primary')
            ->requiresConfirmation()
            ->action(function (Content $record): void {
                $record->update([
                    'status' => ContentStatus::PUBLISHED,
                    'published_at' => now(),
                    'last_published_at' => now(),
                ]);

                if ($record->author) {
                    Notification::make()
                        ->title('Your post has been approved and published!')
                        ->body('The post "'.$record->title.'" is now live.')
                        ->success()
                        ->sendToDatabase($record->author);
                }

                Notification::make()
                    ->title('Post approved and published successfully')
                    ->success()
                    ->send();

                $this->refreshFormData(['status', 'published_at', 'last_published_at']);
            })
            ->visible(fn (Content $record): bool => auth()->user()->can('approve', $record)
                && $record->status === ContentStatus::PENDING
                && ! $record->trashed()
            );
    }
}
