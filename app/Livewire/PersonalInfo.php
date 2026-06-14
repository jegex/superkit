<?php

namespace App\Livewire;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Schema;
use Jeffgreco13\FilamentBreezy\Livewire\PersonalInfo as BasePersonalInfo;

class PersonalInfo extends BasePersonalInfo
{
    public array $only = ['username', 'firstname', 'lastname', 'email'];

    public function mount(): void
    {
        $this->user = filament('filament-breezy')->auth()->user();
        $this->userClass = get_class($this->user);
        $this->form->fill($this->user->only($this->only));
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->model($this->user)
            ->components($this->getProfileFormSchema())
            ->columns(3)
            ->statePath('data');
    }

    public function submit(): void
    {
        $data = collect($this->form->getState())->only($this->only)->all();

        $this->user->update($data);

        $this->form->model($this->user)->saveRelationships();

        Notification::make()
            ->success()
            ->title(__('filament-breezy::default.profile.personal_info.notify'))
            ->send();
    }

    protected function getProfileFormSchema(): array
    {
        return [
            SpatieMediaLibraryFileUpload::make('avatar')
                ->collection('avatars')
                ->conversion('thumb')
                ->disk('public')
                ->avatar()
                ->required()
                ->columnSpan(1),
            Group::make([
                Grid::make()
                    ->schema([
                        TextInput::make('username')
                            ->maxLength(255)
                            ->disabled()
                            ->required(),
                        $this->getEmailComponent(),
                    ]),
                Grid::make()
                    ->schema([
                        TextInput::make('firstname')
                            ->maxLength(255)
                            ->required(),
                        TextInput::make('lastname')
                            ->maxLength(255)
                            ->required(),
                    ]),
            ])->columnSpan(2),
        ];
    }
}
