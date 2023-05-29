<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExperiencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->string('organization');
            $table->string('job_title');
            $table->text('responsibilities_achievements');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('city');
            $table->string('country');
            $table->unsignedBigInteger('experienceable_id');
            $table->string('experienceable_type');
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
        Schema::dropIfExists('experiences');
    }
}
