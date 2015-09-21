<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStarsystemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('star_system_classes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
            $table->engine = 'InnoDB';
        });


        Schema::create('star_systems', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('class_id')->unsigned();
            $table->foreign('class_id')->references('id')->on('star_system_classes');
            $table->integer('mass');
            $table->integer('x');
            $table->integer('y');
            $table->integer('z');

            $table->engine = 'InnoDB';

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
        Schema::drop('star_systems');
        Schema::drop('star_system_classes');

    }
}
