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
        Schema::create('ujian_siswa_hasils', function (Blueprint $table) {
            $table->id();
            $table->string('jawaban')->nullable();
            $table->string('ragu')->nullable();
            $table->string('status'); // 1 = benar, 0 = salah
            $table->bigInteger('skor')->nullable(); // untuk soal essay
            $table->text('komentar_guru')->nullable();
            $table->foreignId('guru_id')->nullable()->constrained('gurus')->onDelete('cascade');
            $table->foreignId('ujian_siswa_id')->constrained('ujian_siswas')->onDelete('cascade');
            $table->unsignedBigInteger('soal_ujian_pg_id')->nullable();
            $table->unsignedBigInteger('soal_ujian_essay_id')->nullable();
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
        Schema::dropIfExists('ujian_siswa_hasils');
    }
};
