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
        Schema::table('pelanggan', function (Blueprint $table) {
            $table->foreignId('ip_pool_id')->nullable()->after('paket_id')->constrained('ip_pool')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pelanggan', function (Blueprint $table) {
            $table->dropForeign(['ip_pool_id']);
            $table->dropColumn('ip_pool_id');
        });
    }
};
