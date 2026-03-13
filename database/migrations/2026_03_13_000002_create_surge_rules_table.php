<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surge_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['peak_hour', 'festival', 'demand_based', 'manual_override']);
            $table->decimal('multiplier', 4, 2)->default(1.00);
            $table->json('conditions')->nullable();  // flexible per-type config
            $table->boolean('is_active')->default(true);
            $table->integer('priority')->default(0);  // higher = checked first (for display; max wins anyway)
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surge_rules');
    }
};
