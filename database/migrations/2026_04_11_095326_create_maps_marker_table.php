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
    Schema::create('maps_marker', function (Blueprint $table) {
        $table->id();
        $table->morphs('markerable');
        $table->decimal('latitude', 10, 7);
        $table->decimal('longitude', 10, 7);
        $table->string('label')->nullable();
        $table->enum('tipe', ['pelanggan', 'odp', 'odc']);
        $table->enum('status', ['online', 'offline', 'nonaktif'])->default('nonaktif');
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maps_marker');
    }
};
