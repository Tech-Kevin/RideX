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
        Schema::table('users', function (Blueprint $table) {
           $table->string('phone', 20)->unique()->after('email');
            $table->enum('role', ['customer', 'driver', 'admin'])->default('customer')->after('phone');
            $table->boolean('is_phone_verified')->default(false)->after('role');
            $table->decimal('current_lat', 10, 7)->nullable()->after('is_phone_verified');
            $table->decimal('current_lng', 10, 7)->nullable()->after('current_lat');

            $table->string('email')->nullable()->change();
            $table->timestamp('email_verified_at')->nullable()->change();
            $table->string('password')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'role',
                'is_phone_verified',
                'current_lat',
                'current_lng'
            ]);
        });
    }
};
