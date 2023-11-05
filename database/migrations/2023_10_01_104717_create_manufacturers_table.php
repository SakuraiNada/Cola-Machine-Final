<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManufacturersTable extends Migration
{
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales');
    }
}

