<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->string('address')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->integer('state_id')->nullable();
            $table->integer('country_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->integer('relation_id')->references('id')->on('relations')->nullable();
            $table->integer('native_village_id')->references('id')->on('native_villages')->nullable();
            $table->integer('occupation_id')->references('id')->on('native_villages')->nullable();
            $table->string('occupation_detail')->nullable();
            $table->integer('education_id')->references('id')->on('educations')->nullable();
            $table->string('education_detail')->nullable();
            $table->date('dob')->nullable();
            $table->string('weight')->nullable();
            $table->string('height')->nullable();
            $table->tinyInteger('age')->nullable();
            $table->tinyInteger('gender')->nullable();
            $table->tinyInteger('blood_group')->nullable();
            $table->tinyInteger('marital_status')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};
