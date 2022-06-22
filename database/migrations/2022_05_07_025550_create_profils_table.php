<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profils', function (Blueprint $table) {
            $table->id();
            $table->enum('nama_pendidikan', ['SMK', 'SMA', 'MA']);
            $table->string('nama_sekolah');
            $table->string('slogan');
            $table->string('singkatan');
            $table->string('logo');
            $table->string('icon');
            $table->string('banner');
            $table->string('npsn');
            $table->text('sambutan');
            $table->text('perkenalan');
            $table->text('alamat');
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
        Schema::dropIfExists('profils');
    }
}
