<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalaryIncreasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salary_increases', function (Blueprint $table) {
            $table->id();
            $table->integer('worker_id');
            $table->integer('old_value')->nullable();
            $table->integer('new_value')->nullable();
            $table->boolean('initial')->default(false);
            $table->date('date');
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
        Schema::dropIfExists('salary_increases');
    }
}
