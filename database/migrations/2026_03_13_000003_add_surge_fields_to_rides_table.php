<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rides', function (Blueprint $table) {
            $table->decimal('surge_multiplier', 4, 2)->default(1.00)->after('estimated_fare');
            $table->string('surge_rule_name')->nullable()->after('surge_multiplier');
        });
    }

    public function down(): void
    {
        Schema::table('rides', function (Blueprint $table) {
            $table->dropColumn(['surge_multiplier', 'surge_rule_name']);
        });
    }
};
