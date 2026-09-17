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
        Schema::create('doa_sakit', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('nama', 50);
            $table->enum('gender', ['Laki-laki', 'Perempuan']);
            $table->char('jenis_penyakit', 50);
            $table->text('catatan')->nullable();
            $table->date('tanggal_doa');
            $table->time('jam_doa');
            $table->char('alamat', 50);
            $table->char('no_hp', 50)->nullable();
            $table->enum('status', ['Selesai', 'Batal/Ditolak', 'Di Terima'])->default('Di Terima');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doa_sakit');
    }
};
