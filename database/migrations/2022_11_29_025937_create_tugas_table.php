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
        Schema::create('tugas', function (Blueprint $table) {
            $table->id();
            $table->string('judul')->nullable();
            $table->string('parent')->default(0);
            $table->string('tipe');
            $table->string('file_or_link')->nullable();
            $table->string('pertemuan');
            $table->text('deskripsi')->nullable();
            $table->dateTime('pengumpulan')->nullable();
            $table->enum('sudah_dinilai', ['0', '1'])->nullable();
            $table->foreignId('mapel_id')->constrained('mapels')->onDelete('cascade');
            $table->foreignId('jadwal_id')->constrained('jadwals')->onDelete('cascade');
            $table->foreignId('guru_id')->nullable()->constrained('gurus')->onDelete('cascade');
            // $table->foreignId('kepsek_id')->nullable()->constrained('kepseks')->onDelete('cascade');
            // $table->foreignId('ortu_id')->nullable()->constrained('ortus')->onDelete('cascade');
            $table->foreignId('siswa_id')->nullable()->constrained('siswas')->onDelete('cascade');
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
        Schema::dropIfExists('tugas');
    }
};
