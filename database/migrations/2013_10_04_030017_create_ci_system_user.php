<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCiSystemUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ci_system_user', function (Blueprint $table) {
            $table->string('id')->unique();
            $table->primary('id');
            $table->string('alias_user_id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('language__id');
            $table->foreign('language__id')->references('id')->on('ci_system_language');
            $table->integer('auth_type__id');
            $table->foreign('auth_type__id')->references('id')->on('ci_sis_master_data');
            $table->string('status')->default('active');
            $table->rememberToken();
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
        Schema::dropIfExists('ci_system_user');
    }
}
