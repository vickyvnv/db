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
        Schema::create('users', function (Blueprint $table) {
            $table->id()->unique()->primary(); // Generates auto-incrementing primary key            $table->string('user_firstname', 100);
            $table->string('user_lastname', 100);
            $table->string('user_firstname', 100);
            $table->string('email', 120);
            $table->string('password', 100);
            $table->string('tel', 100)->nullable();
            $table->string('user_function', 100)->nullable();
            $table->string('user_contractor', 100)->nullable();
            $table->string('team_id', 100)->nullable();
            $table->string('team_name', 255)->nullable();
            $table->string('team_group', 100)->nullable();
            $table->string('user_department', 100)->nullable();
            $table->string('tl', 100)->nullable();
            $table->string('gl', 100)->nullable();
            $table->string('al', 100)->nullable();
            $table->string('company')->nullable();
            $table->string('position')->nullable();
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('username')->unique()->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        // Add index or foreign key constraints if necessary
        // Example:
        // $table->foreign('team_id')->references('id')->on('teams');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
