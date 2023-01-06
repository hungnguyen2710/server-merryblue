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
        Schema::table('fitness_exercise', function (Blueprint $table) {
            $table->string('number_of_reps')->nullable()->after('time');
            $table->string('rest_time')->nullable()->after('number_of_reps');
            $table->longText('tips')->nullable()->after('rest_time');
            $table->string('language_code')->nullable()->after('status');
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
