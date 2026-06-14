<?php

use App\Enums\ContentType;
use App\Models\Content;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

$currentLocale = LaravelLocalization::getCurrentLocale();

Route::view('/', 'pages.index')->name('home');

Route::get('/blog', function () {
    return view('blog.index');
})->name('blog.index');

Route::get('/blog/{slug}', function ($slug) {
    $post = Content::published()
        ->byType(ContentType::Post)
        ->whereJsonContainsLocales('slug', ['en', 'id'], $slug)
        ->with('tags', 'author')
        ->firstOrFail();

    return view('blog.show', ['post' => $post]);
})->name('blog.show');

Route::view('/about', 'pages.about')->name('about');
Route::view('/contact', 'pages.contact')->name('contact');
