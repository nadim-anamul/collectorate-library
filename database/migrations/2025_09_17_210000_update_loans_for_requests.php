<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add requested_at column if missing
        Schema::table('loans', function (Blueprint $table) {
            if (!Schema::hasColumn('loans', 'requested_at')) {
                $table->date('requested_at')->nullable()->after('user_id');
            }
        });
        // Make issued_by_user_id nullable using raw SQL to avoid DBAL requirement
        if (Schema::hasColumn('loans', 'issued_by_user_id')) {
            try {
                DB::statement('ALTER TABLE loans MODIFY issued_by_user_id BIGINT UNSIGNED NULL');
            } catch (\Throwable $e) {
                // Fallback for other drivers (SQLite/Postgres) if needed
                // Ignore if already nullable
            }
        }
    }

    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            if (Schema::hasColumn('loans', 'requested_at')) {
                $table->dropColumn('requested_at');
            }
        });
        if (Schema::hasColumn('loans', 'issued_by_user_id')) {
            try {
                DB::statement('ALTER TABLE loans MODIFY issued_by_user_id BIGINT UNSIGNED NOT NULL');
            } catch (\Throwable $e) {
                // ignore
            }
        }
    }
};


