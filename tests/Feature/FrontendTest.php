<?php

use App\Enums\ContentType;
use App\Models\Content;
use App\Models\User;
use Database\Seeders\LocaleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(LocaleSeeder::class);
});

test('homepage returns a successful response', function () {
    $response = $this->get(route('home'));

    $response->assertStatus(200);
});

test('blog index returns a successful response', function () {
    Content::factory()->published()->create([
        'type' => ContentType::Post,
        'author_id' => User::factory()->create()->id,
    ]);

    $response = $this->get(route('blog.index'));

    $response->assertStatus(200);
});

test('blog detail returns a successful response', function () {
    $content = Content::factory()->published()->create([
        'type' => ContentType::Post,
        'author_id' => User::factory()->create()->id,
    ]);

    $response = $this->get(route('blog.show', $content->slug));

    $response->assertStatus(200);
});

test('about page returns a successful response', function () {
    $response = $this->get(route('about'));

    $response->assertStatus(200);
});

test('contact page returns a successful response', function () {
    $response = $this->get(route('contact'));

    $response->assertStatus(200);
});
