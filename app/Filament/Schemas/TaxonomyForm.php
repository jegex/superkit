<?php

namespace App\Filament\Schemas;

use App\Services\SlugService;
use Closure;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class TaxonomyForm
{
    public static function configure(Schema $schema): Schema
    {
        /** @var Page $livewire */
        $livewire = $schema->getLivewire();
        $resource = $livewire->getResource();
        $type = $resource::getType();
        $hasParent = $resource::$hasParent;

        return $schema
            ->components([
                TextInput::make('name')
                    ->maxLength(255)
                    ->autofocus()
                    ->live(onBlur: true)
                    ->partiallyRenderComponentsAfterStateUpdated(['slug'])
                    ->afterStateUpdated(function (Get $get, Set $set, $state, $record) use ($type, $schema) {
                        if (filled($get('slug'))) {
                            return;
                        }

                        $slug = app(SlugService::class)->generate(
                            $state,
                            $schema->getModel(),
                            $type,
                            $record,
                        );

                        $set('slug', $slug);
                    })
                    ->required(),
                TextInput::make('slug')
                    ->maxLength(255)
                    ->required()
                    ->belowContent('The "slug" is the URL-friendly version of the name')
                    ->rules([
                        fn ($record): Closure => function (string $attribute, $value, Closure $fail) use (
                            $schema,
                            $type,
                            $record
                        ) {
                            if (app(SlugService::class)->isTaken($value, $schema->getModel(), $type, $record)) {
                                $fail(__('validation.unique'));
                            }
                        },
                    ]),
                Select::make('parent_id')
                    ->visible($hasParent)
                    ->columnSpanFull()
                    ->relationship(
                        name: 'parent',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn ($query) => $query->where('type', $type),
                        ignoreRecord: true
                    ),
                Textarea::make('description')
                    ->columnSpanFull(),
                Hidden::make('type')
                    ->default($type),
            ]);
    }
}
