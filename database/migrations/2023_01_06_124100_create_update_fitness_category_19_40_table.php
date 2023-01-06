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
        Schema::table('fitness_category', function (Blueprint $table) {
            $table->longText('description')->nullable()->after('title');
            $table->string('time')->nullable()->after('description');
            $table->string('total_workout')->nullable()->after('time');
            $table->string('calories')->nullable()->after('total_workout');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
};
