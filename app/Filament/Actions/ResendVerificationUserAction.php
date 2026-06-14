<?php

namespace App\Filament\Actions;

use App\Settings\System\MailSettings;
use Exception;
use Filament\Actions\Action;
use Filament\Auth\Notifications\VerifyEmail;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

class ResendVerificationUserAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'resend_verification';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->action(fn (MailSettings $settings, Model $record) => static::doResendEmailVerification($record, $settings));
    }

    /**
     * @throws Exception
     */
    public static function doResendEmailVerification($user, ?MailSettings $settings = null): void
    {
        if (! method_exists($user, 'notify')) {
            $userClass = $user::class;

            throw new Exception("Model [{$userClass}] does not have a [notify()] method.");
        }

        if ($settings->isMailSettingsConfigured()) {
            $notification = new VerifyEmail;
            $notification->url = Filament::getVerifyEmailUrl($user);

            $settings->loadMailSettingsToConfig();

            $user->notify($notification);

            Notification::make()
                ->title('Email Verification Sent')
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title('Can\'t sent email verification!')
                ->body('Please check your Mail Configuration or try again later.')
                ->warning()
                ->send();
        }
    }
}
