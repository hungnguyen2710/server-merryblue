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
        Schema::create('fitness_language', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->string('flag')->nullable();
            $table->tinyInteger('status')->default(1)->comment('0: Ngừng hoat dong | 1: hoat dong');
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
        Schema::dropIfExists('fitness_language');
    }
};
