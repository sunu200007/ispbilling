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
    	Schema::create('paket', function (Blueprint $table) {
       		$table->id();
        	$table->string('nama_paket');
        	$table->string('pool_name');
        	$table->integer('harga');
        	$table->integer('kecepatan_download');
        	$table->integer('kecepatan_upload');
        	$table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
	        $table->timestamps();
    	});
	}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket');
    }
};
