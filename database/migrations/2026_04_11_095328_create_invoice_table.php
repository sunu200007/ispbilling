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
    Schema::create('invoice', function (Blueprint $table) {
        $table->id();
        $table->string('no_invoice')->unique();
        $table->foreignId('pelanggan_id')->constrained('pelanggan');
        $table->integer('jumlah');
        $table->date('tanggal_invoice');
        $table->date('tanggal_jatuh_tempo');
        $table->enum('status', ['unpaid', 'paid', 'overdue'])->default('unpaid');
        $table->string('metode_bayar')->nullable();
        $table->timestamp('dibayar_at')->nullable();
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice');
    }
};
