<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipmentNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipment_notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('kitchen_shipment_id'); 
            $table->integer('center_shipment_id')->default(0); 
            $table->integer('center_id'); 
            $table->integer('kitchen_id'); 
            $table->string('title')->default('');
            $table->integer('sorted')->default(0);
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
        Schema::dropIfExists('shipment_notifications');
    }
}
