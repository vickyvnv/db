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
            $table->integer('requestor_id')->nullable()->change();
            $table->integer('operator_id')->nullable()->change();
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
            $table->dropColumn('requestor_id');
            $table->dropColumn('operator_id');
        });
    }
};
