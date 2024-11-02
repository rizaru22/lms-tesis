<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('materis', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('tipe');
            $table->longText('file_or_link');
            $table->string('pertemuan');
            $table->text('deskripsi');
            $table->foreignId('guru_id')->constrained('gurus')->onDelete('cascade');
            // $table->foreignId('kepsek_id')->constrained('kepseks')->onDelete('cascade');
            // $table->foreignId('ortu_id')->constrained('ortus')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('mapel_id')->constrained('mapels')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('materis');
    }
};