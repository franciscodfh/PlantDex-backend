<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('scientific_name')->nullable();
            $table->string('nickname')->nullable();
            $table->string('family')->nullable();
            $table->enum('type', ['plant', 'fungi'])->default('plant');
            $table->string('image_path')->nullable();
            $table->integer('confidence')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('captured_date')->useCurrent();
            $table->decimal('location_lat', 10, 8)->nullable();
            $table->decimal('location_lng', 11, 8)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plants');
    }
};