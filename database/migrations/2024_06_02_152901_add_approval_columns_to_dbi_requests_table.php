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
            $table->unsignedTinyInteger('is_requestor_submit')->default(0)->after('test_instance');
            $table->unsignedTinyInteger('is_operator_approve')->default(0)->after('is_requestor_submit');
            $table->unsignedTinyInteger('is_admin_approve')->default(0)->after('is_operator_approve');
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
            $table->dropColumn('is_admin_approve');
            $table->dropColumn('is_operator_approve');
            $table->dropColumn('is_requestor_submit');
        });
    }
};
