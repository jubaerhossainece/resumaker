<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTechnologizablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('technologizables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('technology_id');
            $table->unsignedBigInteger('technologizable_id');
            $table->string('technologizable_type');
            $table->timestamps();
            $table->foreign('technology_id')->references('id')->on('technologies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('technologizables');
    }
}
