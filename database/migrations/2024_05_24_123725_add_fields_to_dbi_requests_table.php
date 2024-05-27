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
            $table->string('db_user')->nullable();
            $table->string('source_code')->nullable();
            $table->string('db_instance')->nullable();
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
            $table->dropColumn(['db_user', 'source_code', 'db_instance']);
        });
    }
};
