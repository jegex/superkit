<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('locales', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('script');
            $table->string('native');
            $table->string('regional')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locales');
    }
};
