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
        Schema::table('dbi_requests', function (Blueprint $table) {
            $table->dropColumn(['update_date', 'status_date', 'request_date', 'dbi_status', 'on_hold_flag', 'dbi_flag']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dbi_requests', function (Blueprint $table) {
            $table->dateTime('update_date')->nullable();
            $table->dateTime('status_date')->nullable();
            $table->dateTime('request_date')->nullable();
            $table->string('dbi_status')->default('OP');
            $table->boolean('on_hold_flag')->default(false);
            $table->boolean('dbi_flag')->default(false);
        });
    }
};
