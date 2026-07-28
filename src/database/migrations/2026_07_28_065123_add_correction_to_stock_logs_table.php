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
        Schema::table('stock_logs', function (Blueprint $table) {
            $table->foreignId('corrected_log_id')
                ->nullable()
                ->after('id')
                ->constrained('stock_logs')
                ->nullOnDelete();
            
            $table->text('correction_reason')
                ->nullable()
                ->after('note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_logs', function (Blueprint $table) {
            $table->dropForeign(['corrected_log_id']);
            $table->dropColumn([
                'corrected_log_id',
                'correction_reason',
            ]);
        });
    }
};
