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
        Schema::create('fitness_category', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sort_order');
            $table->string('title');
            $table->string('icon')->nullable();
            $table->string('thumbnail')->nullable();
            $table->tinyInteger('type')->default(1)->comment('1: nam | 0: nu')->nullable();
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
        Schema::dropIfExists('fitness_category');
    }
};
