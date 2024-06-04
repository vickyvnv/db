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
        Schema::create('dbi_request_status', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id');
            $table->foreign('request_id')->references('id')->on('dbi_requests')->onDelete('cascade');
            $table->unsignedTinyInteger('request_status')->default(0)->comment('0: Pending, 1: Approved, 2: Rejected');
            $table->unsignedTinyInteger('operator_status')->default(0)->comment('0: Pending, 1: Approved, 2: Rejected');
            $table->unsignedTinyInteger('dat_status')->default(0)->comment('0: Pending, 1: Approved, 2: Rejected');
            $table->string('operator_comment')->nullable();
            $table->string('dat_comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dbi_request_status');
    }
};
