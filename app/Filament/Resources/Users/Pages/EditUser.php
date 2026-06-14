<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Actions\ResendVerificationUserAction;
use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;
use Relaticle\ActivityLog\Filament\Actions\ActivityLogAction;
use STS\FilamentImpersonate\Actions\Impersonate;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ResendVerificationUserAction::make(),
            ActivityLogAction::make()
                ->label('Audit log')
                ->icon('heroicon-o-shield-check')
                ->visible(fn (): bool => auth()->user()?->isSuperAdmin() ?? false),
            Impersonate::make()->record($this->getRecord()),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
