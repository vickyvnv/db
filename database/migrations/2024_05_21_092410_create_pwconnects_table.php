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
        Schema::create('pwconnects', function (Blueprint $table) {
            $table->bigIncrements('ID')->unsigned();
            $table->string('PWC_NAME', 100)->nullable();
            $table->string('PWC_USER', 100)->nullable();
            $table->string('PWC_PW', 1000)->nullable();
            $table->char('PWC_WRITE', 1)->nullable();
            $table->string('PWC_CAT', 3)->nullable();
            $table->string('PWC_TYP', 10)->nullable();
            $table->string('PWC_GROUP', 30)->nullable();
            $table->char('PWC_ACTIVE_IND', 1)->default('Y');
            $table->char('PWC_CHANGE_TYP', 1)->default('Y');
            $table->string('PWC_CHANGE_COND', 255)->nullable();
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
        Schema::dropIfExists('pwconnects');
    }
};
