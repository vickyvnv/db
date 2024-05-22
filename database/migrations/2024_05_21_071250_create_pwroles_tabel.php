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
        Schema::create('pwroles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('pwr_name')->nullable();
            $table->string('pwr_description')->nullable();
            $table->string('pwc_group')->nullable();
            $table->string('pwr_type')->nullable();
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
        Schema::dropIfExists('pwroles');
    }
};
