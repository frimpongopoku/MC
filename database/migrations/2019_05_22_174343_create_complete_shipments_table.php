<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompleteShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complete_shipments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('description'); 
            $table->integer('expected_amount'); 
            $table->integer('received_amount'); 
            $table->integer('shipment_notification_id');
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
        Schema::dropIfExists('complete_shipments');
    }
}
