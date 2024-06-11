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
        Schema::create('hof_relations', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('inverse')->nullable();
            $table->tinyInteger('status')->default(1)->comment('0:in-active,1:active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hof_relations');
    }
};
