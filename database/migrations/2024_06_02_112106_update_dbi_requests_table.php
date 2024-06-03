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
            $table->dropColumn('request_id');
            $table->dropColumn('sw_version');
            $table->renameColumn('db_instance', 'prod_instance');
            $table->string('test_instance')->after('prod_instance')->nullable();
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
            $table->dropColumn('test_instance');
            $table->renameColumn('prod_instance', 'db_instance');
            $table->unsignedBigInteger('request_id')->nullable();
            $table->unsignedInteger('sw_version')->nullable();
        });
    }
};
