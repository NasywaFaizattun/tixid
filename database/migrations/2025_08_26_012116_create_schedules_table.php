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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            //FK :memanggil data dari table lain
            //$table->foreigId('nama_detail_data')->constrained('sumber_table');
            $table->foreignId('cinema_id')->constrained('cinemas');
            $table->foreignId('movie_id')->constrained('movies');
            $table->time('hours');
            $table->integer('price');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
