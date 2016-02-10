<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateVerificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verification', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_user_id');
            $table->unsignedInteger('country_id');
            $table->string('phone_number', 50);
            $table->enum('verification_status', [
                'unverified',
                'awaiting verification',
                'verified',
                'blocked',
                'failed',
            ])->default('unverified');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('verification');
    }
}
