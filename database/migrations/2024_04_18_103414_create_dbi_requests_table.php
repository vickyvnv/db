<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDbiRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('dbi_requests', function (Blueprint $table) {
            $table->id()->unique()->primary();
            $table->string('request_id')->nullable();
            $table->string('requestor_id')->nullable();
            $table->string('operator_id')->nullable();
            $table->string('category');
            $table->string('priority_id');
            $table->string('sw_version');
            $table->string('dbi_type');
            $table->string('tt_id');
            $table->string('serf_cr_id');
            $table->string('reference_dbi');
            $table->text('brief_desc');
            $table->text('problem_desc');
            $table->text('business_impact');
            $table->dateTime('update_date')->nullable();
            $table->dateTime('status_date')->nullable();
            $table->dateTime('request_date')->nullable();
            $table->string('dbi_status')->default('OP');
            $table->boolean('on_hold_flag')->default(false);
            $table->boolean('dbi_flag')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dbi_requests');
    }
}
