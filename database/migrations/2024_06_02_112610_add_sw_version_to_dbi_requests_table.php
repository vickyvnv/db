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
            $table->unsignedBigInteger('sw_version')->nullable();
            $table->foreign('sw_version')->references('id')->on('markets')->onDelete('cascade');
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
            $table->dropColumn('sw_version');
        });
    }
};
