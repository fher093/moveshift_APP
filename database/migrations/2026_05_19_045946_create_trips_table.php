<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Crear tabla vehicles PRIMERO
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('users')->onDelete('cascade');
            $table->string('brand');
            $table->string('model');
            $table->string('plate')->unique();
            $table->string('color');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Crear tabla trips SEGUNDO (para poder usar vehicle_id)
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('vehicle_id')->nullable()->constrained('vehicles')->nullOnDelete();
            
            $table->string('origin_zone');
            $table->string('destination_zone');
            $table->dateTime('departure_time');
            $table->integer('total_seats');
            $table->integer('available_seats');
            $table->decimal('price', 8, 2)->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // El orden de eliminación debe ser inverso al de creación
        Schema::dropIfExists('trips');
        Schema::dropIfExists('vehicles');
    }
};