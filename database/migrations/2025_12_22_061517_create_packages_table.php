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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->smallInteger('max_capacity')->nullable();
            $table->smallInteger('min_capacity')->nullable();
            $table->enum('week_type', ['weekdays', 'weekends']);
            $table->integer('price');
            $table->enum('price_type', ['pack', 'night']);
            $table->json('benefits')->nullable();
            $table->string('total_stays')->nullable();
            $table->boolean('is_published')->default(0);
            $table->text('photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
