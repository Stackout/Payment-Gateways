<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GatewaySettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Migrations to create gateway settings table.
         */
        Schema::create('gateway_settings', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('key')->index();
            $table->text('value')->nullable();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('field')->nullable();
            $table->integer('active')->nullable();
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
        Schema::dropIfExists('gateway_settings');
    }
}
