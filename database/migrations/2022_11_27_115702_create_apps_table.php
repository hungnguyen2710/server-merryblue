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
        Schema::create('apps', function (Blueprint $table) {
            $table->id();
            $table->string('name_project');
            $table->string('name_in_store')->nullable();
            $table->string('app_id')->nullable();
            $table->string('category_name')->nullable();
            $table->string('company')->nullable();
            $table->string('size')->nullable();
            $table->string('icon')->nullable();
            $table->string('description')->nullable();
            $table->string('link_ios')->nullable();
            $table->string('link_android')->nullable();
            $table->dateTime('time_update_store')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1: hoạt động | 0: ngừng hoạt động');
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
        Schema::dropIfExists('apps');
    }
};
