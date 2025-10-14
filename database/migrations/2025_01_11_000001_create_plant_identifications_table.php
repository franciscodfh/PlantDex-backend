<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plant_identifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('image_path');
            $table->string('scientific_name');
            $table->string('common_name')->nullable();
            $table->string('family')->nullable();
            $table->integer('confidence_score')->default(0);
            $table->boolean('verified')->default(false);
            $table->foreignId('verified_by_user_id')->nullable()->constrained('users');
            $table->integer('verification_count')->default(0);
            $table->decimal('location_lat', 10, 8)->nullable();
            $table->decimal('location_lng', 11, 8)->nullable();
            $table->timestamp('captured_date');
            $table->timestamps();
            
            $table->index(['scientific_name', 'verified']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plant_identifications');
    }
};
