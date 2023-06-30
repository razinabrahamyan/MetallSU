<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logistics', function (Blueprint $table) {
            $table->id();
            $table->string ('name');
            $table->string ('phone');
            $table->string ('car_type');
            $table->string ('car_number');
            $table->string ('date');
            $table->integer('query_id');
            $table->integer('cutter')->default(0);
            $table->integer('loader')->default(0);
            $table->integer('oxygen')->default(0);
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
        Schema::dropIfExists('logistics');
    }
}
