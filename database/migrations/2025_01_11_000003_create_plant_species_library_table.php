<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plant_species_library', function (Blueprint $table) {
            $table->id();
            $table->string('scientific_name')->unique();
            $table->json('common_names')->nullable();
            $table->string('family')->nullable();
            $table->string('genus')->nullable();
            $table->json('image_samples')->nullable();
            $table->integer('identification_count')->default(0);
            $table->decimal('accuracy_rate', 5, 2)->default(0);
            $table->json('botanical_data')->nullable();
            $table->timestamps();
            
            $table->index('scientific_name');
            $table->index('identification_count');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plant_species_library');
    }
};
