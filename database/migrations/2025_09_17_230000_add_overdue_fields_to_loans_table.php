<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            if (!Schema::hasColumn('loans', 'overdue_at')) {
                $table->date('overdue_at')->nullable()->after('returned_at');
            }
            if (!Schema::hasColumn('loans', 'due_at')) {
                $table->date('due_at')->nullable();
            }
            $table->index(['due_at']);
        });
    }

    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            if (Schema::hasColumn('loans', 'overdue_at')) {
                $table->dropColumn('overdue_at');
            }
            // keep due_at
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            try { $table->dropIndex(['due_at']); } catch (\Throwable $e) {}
        });
    }
};


