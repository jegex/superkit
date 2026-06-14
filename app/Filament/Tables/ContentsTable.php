<?php

namespace App\Filament\Tables;

use App\Enums\ContentStatus;
use App\Models\Content;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ContentsTable
{
    public static function configure(Table $table): Table
    {
        /** @var Page $livewire */
        $livewire = $table->getLivewire();
        $resource = $livewire->getResource();
        $type = $resource::getType();

        return $table
            ->defaultSort('updated_at', 'desc')
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                TextColumn::make('author.firstname')
                    ->label('Author')
                    ->formatStateUsing(fn (Model $record) => "{$record->author->firstname} {$record->author->lastname}")
                    ->searchable(['firstname', 'lastname'])
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('type')
                    ->badge()
                    ->visible($type === null),
                ToggleColumn::make('is_featured')
                    ->label('Featured')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible(function () {
                        $user = auth()->user();

                        return $user && $user->hasAnyRole(['super_admin', 'admin', 'editor']);
                    }),
                TextColumn::make('published_at')
                    ->dateTime()
                    ->label('Published')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->since()
                    ->label('Last update')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(ContentStatus::class),
                SelectFilter::make('author_id')
                    ->label('Author')
                    ->relationship('author', 'firstname')
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->firstname} {$record->lastname}")
                    ->searchable()
                    ->preload()
                    ->visible(fn () => auth()->user()->hasAnyRole(['super_admin', 'admin', 'editor'])),
                Filter::make('is_featured')
                    ->label('Featured Posts')
                    ->query(fn (Builder $query): Builder => $query->where('is_featured', true))
                    ->visible(fn () => auth()->user()->hasAnyRole(['super_admin', 'admin', 'editor'])),
                Filter::make('published')
                    ->label('Published Posts')
                    ->query(fn (Builder $query): Builder => $query->published()),
                Filter::make('published_at')
                    ->label('Published This Month')
                    ->query(fn (Builder $query): Builder => $query->whereMonth('published_at', now()->month)),
                Filter::make('pending_approval')
                    ->label('Pending Approval')
                    ->query(fn (Builder $query): Builder => $query->where('status', ContentStatus::PENDING))
                    ->toggle(),
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make()
                        ->visible(fn (Model $record) => auth()->user()->can('update', $record)),
                    Action::make('publish')
                        ->label(fn ($record): string => $record->status === ContentStatus::PENDING ? 'Approve & Publish' : 'Publish')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function (Content $record, $action): void {
                            $wasPending = $record->status === ContentStatus::PENDING;

                            $record->update([
                                'status' => ContentStatus::PUBLISHED,
                                'published_at' => now(),
                                'last_published_at' => now(),
                            ]);

                            if ($wasPending && $record->author) {
                                Notification::make()
                                    ->title('Your post has been approved and published!')
                                    ->body('The post "'.$record->title.'" is now live.')
                                    ->success()
                                    ->sendToDatabase($record->author);
                            }

                            Notification::make()
                                ->title($wasPending ? 'Post approved and published' : 'Post published successfully')
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->visible(fn (Content $record) => auth()->user()->can('publish', $record) &&
                            $record->status !== ContentStatus::PUBLISHED
                        ),
                    Action::make('duplicate')
                        ->icon(Heroicon::OutlinedDocumentDuplicate)
                        ->action(function (Content $record, $action): void {
                            $user = auth()->user();

                            $duplicate = $record->replicate();
                            $duplicate->title = collect($record->getTranslations('title'))
                                ->map(fn ($title) => 'Copy of '.$title)
                                ->all();
                            $duplicate->slug = collect($record->getTranslations('slug'))
                                ->map(fn ($slug) => str($slug)->slug().'-copy')
                                ->all();
                            $duplicate->status = ContentStatus::DRAFT;
                            $duplicate->published_at = null;
                            $duplicate->is_featured = false;

                            if ($user->hasRole('author')) {
                                $duplicate->author_id = $user->id;
                            }

                            $duplicate->save();

                            $duplicate->syncTags($record->tags);

                            foreach ($record->getMedia('featured') as $media) {
                                $media->copy($duplicate, 'featured');
                            }

                            $action->getLivewire()->redirect($action->getLivewire()->getResource()::getUrl('edit', ['record' => $duplicate]));
                        }),
                    DeleteAction::make()
                        ->visible(fn (Content $record) => auth()->user()->can('delete', $record)),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
