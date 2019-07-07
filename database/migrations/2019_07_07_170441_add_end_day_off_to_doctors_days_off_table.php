<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEndDayOffToDoctorsDaysOffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('doctors_days_off', function (Blueprint $table) {
            $table->date('end_day_off')->nullable()->after('day_off');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('doctors_days_off', function (Blueprint $table) {
            $table->dropColumn(['end_day_off']);
        });
    }
}
