<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAttendancesTable extends Migration
{
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id')->nullable(false)->change();
        });
    }
}
