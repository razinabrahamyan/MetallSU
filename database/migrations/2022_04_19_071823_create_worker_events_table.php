<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkerEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('worker_events', function (Blueprint $table) {
            $table->id();
            $table->integer('worker_id');
            $table->date('date');
            $table->integer('type_id');
            $table->enum('status',[0,1])->default(1)->comment('0:inactive,1:active');
            $table->json('additional')->nullable();
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
        Schema::dropIfExists('worker_events');
    }
}
