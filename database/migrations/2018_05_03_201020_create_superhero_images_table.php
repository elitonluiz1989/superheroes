<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuperheroImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('superhero_images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('superhero_id')->unsigned();
            $table->text('superhero_image');
            $table->timestamps();
        });

        Schema::table('superhero_images', function($table) {
            $table->foreign('superhero_id')->reference('id')->on('superhero')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('superhero_images');
    }
}
