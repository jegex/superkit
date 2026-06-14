<?php

namespace App\Console\Commands;

use App\Models\User;
use Database\Seeders\DemoSeeder;
use Database\Seeders\LocaleSeeder;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\intro;
use function Laravel\Prompts\outro;
use function Laravel\Prompts\password;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

#[Signature('superkit:setup')]
#[Description('Setup Superkit with initial configuration and demo data')]
class SuperkitSetupCommand extends Command
{
    public function handle(): int
    {
        intro('Superkit Setup Wizard v1');

        $appName = text(
            label: 'What is your application name?',
            default: 'Superkit',
            required: true,
        );

        $appUrl = text(
            label: 'Application URL',
            default: 'http://localhost',
            required: true,
        );

        $adminEmail = text(
            label: 'Admin email',
            default: 'admin@superkit.test',
            required: true,
            validate: fn (string $value) => match (true) {
                ! filter_var($value, FILTER_VALIDATE_EMAIL) => 'Please enter a valid email address.',
                default => null,
            },
        );

        $adminPassword = password(
            label: 'Admin password (min 8 characters)',
            required: true,
            validate: fn (string $value) => match (true) {
                strlen($value) < 8 => 'Password must be at least 8 characters.',
                default => null,
            },
        );

        $defaultLang = select(
            label: 'Default language',
            options: ['en' => 'English', 'id' => 'Indonesian'],
            default: 'en',
        );

        config(['app.name' => $appName]);
        config(['app.url' => $appUrl]);

        $this->info('Updating environment file...');
        $this->updateEnv('APP_NAME', "\"{$appName}\"");
        $this->updateEnv('APP_URL', $appUrl);

        $migrated = confirm(label: 'Run database migrations?', default: true);

        if ($migrated) {
            $this->call('migrate', ['--force' => true]);
        }

        if ($migrated) {
            $this->call('db:seed', ['--class' => LocaleSeeder::class, '--force' => true]);
        }

        if ($migrated) {
            $user = User::updateOrCreate([
                'email' => $adminEmail,
            ], [
                'username' => 'admin',
                'firstname' => 'Admin',
                'lastname' => 'Super',
                'password' => Hash::make($adminPassword),
                'timezone' => 'UTC',
            ]);

            $this->call('shield:install', ['panel' => 'admin', '--no-interaction' => true]);
            $this->call('shield:generate', ['--all' => true, '--no-interaction' => true, '--panel' => 'admin']);
            $this->call('shield:seeder', ['--generate' => true, '--option' => 'permissions_via_roles', '--no-interaction' => true]);
            $this->call('shield:super-admin', ['--user' => $user->id, '--panel' => 'admin', '--no-interaction' => true]);
        }

        if ($migrated && confirm(label: 'Seed demo data (blog posts, categories, pages)?', default: true)) {
            $this->call('db:seed', ['--class' => DemoSeeder::class, '--force' => true]);
        }

        outro('Setup complete! You can now login to the admin panel.');

        $this->table(
            ['Item', 'Value'],
            [
                ['Admin URL', rtrim($appUrl, '/').'/admin'],
                ['Email', $adminEmail],
                ['Application', $appName],
            ]
        );

        return self::SUCCESS;
    }

    private function updateEnv(string $key, string $value): void
    {
        $path = base_path('.env');

        if (! file_exists($path)) {
            return;
        }

        $content = file_get_contents($path);

        $pattern = "/^{$key}=.*/m";

        if (preg_match($pattern, $content)) {
            $content = preg_replace($pattern, "{$key}={$value}", $content);
        } else {
            $content .= "\n{$key}={$value}\n";
        }

        file_put_contents($path, $content);
    }
}
