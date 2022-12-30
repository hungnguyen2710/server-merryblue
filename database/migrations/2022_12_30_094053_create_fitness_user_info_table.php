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
        Schema::create('fitness_user_info', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('fitness_user_id');
            $table->tinyInteger('gender')->default(1)->comment('1: nam | 0: nu');
            $table->bigInteger('weight')->nullable();
            $table->bigInteger('height')->nullable();
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
        Schema::dropIfExists('fitness_user_info');
    }
};
