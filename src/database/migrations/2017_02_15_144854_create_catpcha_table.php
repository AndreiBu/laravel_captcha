<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCatpchaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
     Schema::create('captcha', function (Blueprint $table) {
          $table->string('a')->nullable();
          $table->string('b')->nullable();
          $table->string('c')->nullable();
          $table->string('md5')->nullable();
          $table->dateTime('dat')->nullable();
          $table->integer('redraw')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('captcha');
    }
}
