<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEducationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('education', function (Blueprint $table) {
            $table->id();
            $table->string('study_field');
            $table->string('degree');
            $table->string('institution_name');
            $table->string('result');
            $table->string('city');
            $table->string('country');
            $table->date('grad_date');
            $table->boolean('is_current')->default(false);
            $table->unsignedBigInteger('educationable_id');
            $table->string('educationable_type');
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
        Schema::dropIfExists('education');
    }
}
