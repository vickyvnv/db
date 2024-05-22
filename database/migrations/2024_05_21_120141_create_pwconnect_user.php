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
        Schema::create('pwconnect_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pwconnect_id');
            $table->unsignedBigInteger('pwuser_id');
            $table->timestamps();
        
            $table->foreign('pwconnect_id')->references('id')->on('pwconnects')->onDelete('cascade');
            $table->foreign('pwuser_id')->references('id')->on('pw_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pwconnect_user');
    }
};
