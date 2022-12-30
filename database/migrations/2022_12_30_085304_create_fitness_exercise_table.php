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
        Schema::create('fitness_exercise', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('fitness_category_id');
            $table->string('title');
            $table->bigInteger('time');
            $table->double('calories');
            $table->string('thumbnail');
            $table->string('image_action');
            $table->longText('description');
            $table->tinyInteger('status')->default(1)->comment('1: hoat dong | 0: ngung hoat dong')->nullable();
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
        Schema::dropIfExists('fitness_exercise');
    }
};
