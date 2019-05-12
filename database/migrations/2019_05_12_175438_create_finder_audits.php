<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinderAudits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finder_audits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('finder_id');
            $table->text('action');
            $table->string('ip_address',50)->nullable();
            $table->string('device',50)->nullable();
            $table->string('browser',50)->nullable();
            $table->string('operating_system',50)->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('finder_audits');
    }
}
