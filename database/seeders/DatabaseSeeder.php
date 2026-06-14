<?php

namespace Database\Seeders;

use App\Enums\ContentStatus;
use App\Enums\ContentType;
use App\Enums\TaxonomyType;
use App\Models\Content;
use App\Models\Taxonomy;
use App\Models\User;
use Datlechin\FilamentMenuBuilder\Models\Menu;
use Illuminate\Database\Seeder;
use Symfony\Component\Process\Process;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (! User::whereEmail('admin@admin.com')->exists()) {
            User::factory()->create([
                'username' => 'admin',
                'firstname' => 'Admin',
                'lastname' => 'Admin',
                'email' => 'admin@admin.com',
            ]);
        }

        User::factory(10)->create();

        $this->call([
            LocaleSeeder::class,
        ]);

        Taxonomy::create([
            'name' => 'Uncategories',
            'slug' => 'uncategories',
            'type' => TaxonomyType::Category,
        ]);

        Content::create([
            'title' => [
                'en' => 'Hello World!',
                'id' => 'Halo Dunia!',
            ],
            'author_id' => 1,
            'slug' => [
                'en' => 'hello-world',
                'id' => 'halo-dunia',
            ],
            'type' => ContentType::Post,
            'content' => [
                'en' => 'This is the first post',
                'id' => 'Ini adalah post pertama',
            ],
            'status' => ContentStatus::PUBLISHED,
            'published_at' => now(),
        ]);

        Content::create([
            'title' => 'Sample Page',
            'author_id' => 1,
            'slug' => 'sample-page',
            'type' => ContentType::Page,
            'content' => 'This is the first page',
        ]);

        Menu::create([
            'name' => 'Main Menu',
        ]);

        $this->call([
            DemoSeeder::class,
        ]);

        $this->runArtisan('shield:install', ['admin', '--no-interaction']);
        $this->runArtisan('shield:generate', ['--all', '--no-interaction', '--panel=admin']);
        $this->runArtisan('shield:seeder', ['--generate', '--option=permissions_via_roles', '--no-interaction']);
        $this->runArtisan('shield:super-admin', ['--user=1', '--panel=admin', '--no-interaction']);
    }

    private function runArtisan(string $command, array $arguments = []): void
    {
        $process = new Process([
            PHP_BINARY,
            base_path('artisan'),
            $command,
            ...$arguments,
        ]);

        $process->setTimeout(120);
        $process->run();
    }
}
