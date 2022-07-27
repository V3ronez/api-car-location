<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('clients_id');
            $table->unsignedBigInteger('car_id');
            $table->dateTime('date_start_route');
            $table->dateTime('date_final_undefined_route');
            $table->dateTime('date_final_defined_route');
            $table->float('price_daily', 8,2);
            $table->integer('km_start');
            $table->integer('km_final');
            $table->timestamps();

            //foreign;
            $table->foreign('clients_id')->references('id')->on('clients');
            $table->foreign('car_id')->references('id')->on('cars');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locations');
    }
}
