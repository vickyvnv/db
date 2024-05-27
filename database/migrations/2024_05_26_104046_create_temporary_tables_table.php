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
        Schema::create('temporary_tables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dbi_request_id');
            $table->unsignedBigInteger('user_id');
            $table->string('table_name');
            $table->string('type');
            $table->date('drop_date');
            $table->text('sql');
            $table->timestamps();
    
            $table->foreign('dbi_request_id')->references('id')->on('dbi_requests')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('temporary_tables');
    }
};
