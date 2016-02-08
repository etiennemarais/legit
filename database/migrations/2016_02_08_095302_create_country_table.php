<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateCountryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('country', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('country_code', [
                'olxtz',
                'olxug',
                'olxgh',
                'olxke',
                'olxng',
                'olxza',
            ]);
            $table->enum('country_iso', [
                'TZ',
                'UG',
                'GH',
                'KE',
                'NG',
                'ZA',
            ]);
            $table->string('api_key', 40);
            $table->enum('status', [
                'enabled',
                'disabled',
            ]);
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
        Schema::drop('country');
    }
}
