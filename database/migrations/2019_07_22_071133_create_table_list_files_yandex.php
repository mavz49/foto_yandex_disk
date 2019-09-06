<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableListFilesYandex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('list_files_yandex', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('part')->nullable();
            $table->string('name')->nullable();
            $table->string('md5')->nullable();
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
        Schema::dropIfExists('table_list_files_yandex');
    }
}
