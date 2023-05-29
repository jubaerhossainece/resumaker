<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('certifiable_id');
            $table->string('certifiable_type');
            $table->string('name');
            $table->string('issuing_org')->nullable();
            $table->string('credential_url')->nullable();
            $table->date('issue_date')->nullable();
            $table->date('exp_date')->nullable();
            $table->boolean('is_no_exp')->default(false);
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
        Schema::dropIfExists('certifications');
    }
}
