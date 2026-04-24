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
            $table->uuid('team_id')->nullable()->after('id')->index();
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->uuid('team_id')->nullable()->after('id')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('team_id');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('team_id');
        });
    }
};
