<?php

use App\Enums\TaxonomyType;
use App\Models\Taxonomy;
use App\Rules\UniqueSlug;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can create taxonomy with translatable fields', function () {
    $taxonomy = Taxonomy::create([
        'name' => ['en' => 'Test Category', 'id' => 'Kategori Tes'],
        'type' => TaxonomyType::Category,
    ]);

    expect($taxonomy->getTranslations('name'))->toBe(['en' => 'Test Category', 'id' => 'Kategori Tes'])
        ->and($taxonomy->getTranslations('slug'))->toBe(['en' => 'test-category', 'id' => 'kategori-tes'])
        ->and($taxonomy->type)->toBe(TaxonomyType::Category);
});

test('can create taxonomy with tag type', function () {
    $taxonomy = Taxonomy::create([
        'name' => ['en' => 'PHP'],
        'type' => TaxonomyType::Tag,
    ]);

    expect($taxonomy->type)->toBe(TaxonomyType::Tag)
        ->and($taxonomy->getTranslation('slug', 'en'))->toBe('php');
});

test('unique slug rejects duplicate in same locale', function () {
    Taxonomy::create([
        'name' => ['en' => 'Same Slug'],
        'type' => TaxonomyType::Category,
    ]);

    $rule = new UniqueSlug(
        modelClass: Taxonomy::class,
        locale: 'en',
        type: TaxonomyType::Category->value,
    );

    $failed = false;
    $rule->validate('slug', 'same-slug', function () use (&$failed) {
        $failed = true;
    });

    expect($failed)->toBeTrue();
});

test('unique slug allows same value in different locale', function () {
    Taxonomy::create([
        'name' => ['en' => 'Same Slug', 'id' => 'Other'],
        'type' => TaxonomyType::Category,
    ]);

    $rule = new UniqueSlug(
        modelClass: Taxonomy::class,
        locale: 'id',
        type: TaxonomyType::Category->value,
    );

    $failed = false;
    $rule->validate('slug', 'same-slug', function () use (&$failed) {
        $failed = true;
    });

    expect($failed)->toBeFalse();
});

test('unique slug allows same slug for different type', function () {
    Taxonomy::create([
        'name' => ['en' => 'General'],
        'type' => TaxonomyType::Category,
    ]);

    $rule = new UniqueSlug(
        modelClass: Taxonomy::class,
        locale: 'en',
        type: TaxonomyType::Tag->value,
    );

    $failed = false;
    $rule->validate('slug', 'general', function () use (&$failed) {
        $failed = true;
    });

    expect($failed)->toBeFalse();
});

test('unique slug respects ignore id', function () {
    $taxonomy = Taxonomy::create([
        'name' => ['en' => 'Keep'],
        'type' => TaxonomyType::Category,
    ]);

    $rule = new UniqueSlug(
        modelClass: Taxonomy::class,
        locale: 'en',
        type: TaxonomyType::Category->value,
        ignoreId: $taxonomy->id,
    );

    $failed = false;
    $rule->validate('slug', 'keep', function () use (&$failed) {
        $failed = true;
    });

    expect($failed)->toBeFalse();
});

test('can update taxonomy translatable fields', function () {
    $taxonomy = Taxonomy::create([
        'name' => ['en' => 'Original'],
        'type' => TaxonomyType::Category,
    ]);

    $taxonomy->setTranslation('name', 'en', 'Updated');
    $taxonomy->setTranslation('name', 'id', 'Diperbarui');
    $taxonomy->save();

    $taxonomy->refresh();

    expect($taxonomy->getTranslations('name'))->toBe(['en' => 'Updated', 'id' => 'Diperbarui'])
        ->and($taxonomy->getTranslations('slug'))->toBe(['en' => 'updated', 'id' => 'diperbarui']);
});

test('can soft delete taxonomy', function () {
    $taxonomy = Taxonomy::create([
        'name' => ['en' => 'Temp'],
        'type' => TaxonomyType::Tag,
    ]);

    $taxonomy->delete();

    expect(Taxonomy::count())->toBe(0)
        ->and(Taxonomy::withTrashed()->count())->toBe(1);
});

test('unique slug ignores soft deleted records', function () {
    $taxonomy = Taxonomy::create([
        'name' => ['en' => 'Deleted'],
        'type' => TaxonomyType::Category,
    ]);

    $taxonomy->delete();

    $rule = new UniqueSlug(
        modelClass: Taxonomy::class,
        locale: 'en',
        type: TaxonomyType::Category->value,
    );

    $failed = false;
    $rule->validate('slug', 'deleted', function () use (&$failed) {
        $failed = true;
    });

    expect($failed)->toBeFalse();
});

test('slug gets auto generated from name', function () {
    $taxonomy = Taxonomy::create([
        'name' => ['en' => 'Hello World', 'id' => 'Halo Dunia'],
        'type' => TaxonomyType::Tag,
    ]);

    expect($taxonomy->getTranslations('slug'))->toBe(['en' => 'hello-world', 'id' => 'halo-dunia']);
});
