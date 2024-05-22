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
        Schema::create('pwgroup_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pwgroup_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('pwgroup_id')->references('id')->on('pwgroups')->onDelete('cascade');
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
        Schema::dropIfExists('pwgroup_user');
    }
};
