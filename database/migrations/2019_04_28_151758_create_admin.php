<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdmin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('firstname',50);
            $table->string('middlename',50)->nullable();
            $table->string('lastname',50);
            $table->string('image',100)->nullable();
            $table->string('email',50)->nullable();
            $table->string('username',50)->nullable();
            $table->string('password',100)->nullable();
            $table->string('account_type',20);
            $table->integer('account_line');
            $table->integer('account_status');
            $table->softDeletes();
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
        Schema::dropIfExists('admins');
    }
}
