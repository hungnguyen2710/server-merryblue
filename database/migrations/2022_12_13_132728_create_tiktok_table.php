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
        Schema::create('tiktok', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->string('version');
            $table->string('api')->nullable();
            $table->tinyInteger('status')->nullable()->comment('1: thành công | 0: thất bại');
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
        Schema::dropIfExists('tiktok');
    }
};
