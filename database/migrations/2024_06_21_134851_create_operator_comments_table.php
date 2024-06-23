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
        Schema::create('operator_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dbi_request_id');
            $table->text('comment');
            $table->timestamps();

            $table->foreign('dbi_request_id')->references('id')->on('dbi_requests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('operator_comments');
    }
};
