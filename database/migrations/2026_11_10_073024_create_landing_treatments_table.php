<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('landing_treatments', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('category', 100);
            $table->string('name', 120);
            $table->text('description');
            $table->json('problems');
            $table->json('details');
            $table->unsignedInteger('price');
            $table->string('image');
            $table->string('image_alt', 200);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['is_active', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('landing_treatments');
    }
};
