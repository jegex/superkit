<?php

namespace Database\Seeders;

use App\Enums\ContentStatus;
use App\Enums\ContentType;
use App\Enums\TaxonomyType;
use App\Models\Content;
use App\Models\Taxonomy;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first() ?? User::factory()->create([
            'username' => 'admin',
            'firstname' => 'Admin',
            'lastname' => 'Super',
            'email' => 'admin@superkit.test',
        ]);

        $categories = collect(['Technology', 'Design', 'Business']);
        $categoryModels = $categories->map(fn ($name) => Taxonomy::create([
            'name' => $name,
            'slug' => str($name)->slug(),
            'type' => TaxonomyType::Category,
        ]));

        $tags = collect(['Laravel', 'Filament', 'PHP', 'JavaScript', 'Tailwind']);
        $tagModels = $tags->map(fn ($name) => Taxonomy::create([
            'name' => $name,
            'slug' => str($name)->slug(),
            'type' => TaxonomyType::Tag,
        ]));

        $posts = [
            [
                'title' => 'Getting Started with Laravel 13',
                'content' => '<p>Laravel 13 brings a host of new features including better attribute support, improved performance, and more expressive syntax. In this post, we\'ll explore what\'s new and how to get started.</p><p>First, make sure you have PHP 8.3+ and Composer installed. Then run:</p><pre><code>composer create-project laravel/laravel my-app</code></pre>',
                'category' => 'Technology',
                'tags' => ['Laravel', 'PHP'],
            ],
            [
                'title' => 'Building Admin Panels with Filament 5',
                'content' => '<p>Filament 5 is the most exciting release yet. With a completely revamped schema system, new form components, and better performance, it\'s the perfect tool for building admin panels.</p><p>The new Schema API makes form building more intuitive and powerful than ever before.</p>',
                'category' => 'Technology',
                'tags' => ['Filament', 'PHP', 'Laravel'],
            ],
            [
                'title' => 'Mastering Tailwind CSS v4',
                'content' => '<p>Tailwind CSS v4 introduces a new engine built on Lightning CSS, offering incredible performance improvements and a simplified configuration system.</p><p>In this guide, we\'ll cover the key changes and how to upgrade your existing projects.</p>',
                'category' => 'Design',
                'tags' => ['Tailwind', 'JavaScript'],
            ],
            [
                'title' => 'Why Multi-Language Support Matters',
                'content' => '<p>In today\'s globalized world, supporting multiple languages is no longer optional. Learn how to implement multi-language support in your Laravel applications using Spatie\'s translation packages.</p>',
                'category' => 'Business',
                'tags' => ['Laravel', 'PHP'],
            ],
            [
                'title' => '10 Tips for Better Code Quality',
                'content' => '<p>Writing clean, maintainable code is a skill that takes time to develop. Here are our top 10 tips for improving your code quality, from naming conventions to testing strategies.</p>',
                'category' => 'Technology',
                'tags' => ['PHP', 'JavaScript'],
            ],
            [
                'title' => 'Introduction to Livewire 4',
                'content' => '<p>Livewire 4 brings significant improvements to reactive UI components in Laravel. Learn how to build dynamic interfaces without writing JavaScript.</p>',
                'category' => 'Technology',
                'tags' => ['Laravel', 'PHP'],
            ],
        ];

        foreach ($posts as $i => $postData) {
            $content = Content::create([
                'title' => $postData['title'],
                'slug' => str($postData['title'])->slug(),
                'content' => $postData['content'],
                'excerpt' => str($postData['content'])->stripTags()->limit(150)->value(),
                'type' => ContentType::Post,
                'status' => ContentStatus::PUBLISHED,
                'published_at' => now()->subDays(6 - $i),
                'author_id' => $admin->id,
            ]);

            $category = $categoryModels->firstWhere('name', $postData['category']);
            if ($category) {
                $content->attachTag($category);
            }

            foreach ($postData['tags'] as $tagName) {
                $tag = $tagModels->firstWhere('name', $tagName);
                if ($tag) {
                    $content->attachTag($tag);
                }
            }
        }

        Content::create([
            'title' => 'Welcome to Superkit',
            'slug' => 'about',
            'content' => '<p>Superkit is a powerful Laravel starter kit built with Filament 5. It comes with a built-in CMS, multi-language support, role management, media library, and much more.</p><p>Whether you\'re building a blog, a corporate website, or a SaaS application, Superkit gives you the foundation you need to get started quickly.</p>',
            'excerpt' => 'Welcome to Superkit - your starting point for building amazing Laravel applications.',
            'type' => ContentType::Page,
            'status' => ContentStatus::PUBLISHED,
            'published_at' => now(),
            'author_id' => $admin->id,
        ]);
    }
}
