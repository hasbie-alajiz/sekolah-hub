<?php

declare(strict_types=1);

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
        Schema::table('registration_values', function (Blueprint $table) {
            $table->unique(['registration_id', 'field_id'], 'reg_field_unique');
        });

        Schema::table('registrations', function (Blueprint $table) {
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registration_values', function (Blueprint $table) {
            $table->dropUnique('reg_field_unique');
        });

        Schema::table('registrations', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });
    }
};
