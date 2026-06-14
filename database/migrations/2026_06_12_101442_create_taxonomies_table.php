<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('taxonomies', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->json('slug');
            $table->json('description')->nullable();
            $table->string('type');
            $table->nestedSet();
            $table->integer('order_column')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['id', 'type', 'deleted_at']);
            $table->index(['type', '_lft', '_rgt']);
        });

        Schema::create('taxonomables', function (Blueprint $table) {
            $table->foreignId('tag_id')->constrained('taxonomies')->cascadeOnDelete();

            $table->morphs('taxonomable');

            $table->unique(['tag_id', 'taxonomable_id', 'taxonomable_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('taxonomables');
        Schema::dropIfExists('taxonomies');
    }
};
