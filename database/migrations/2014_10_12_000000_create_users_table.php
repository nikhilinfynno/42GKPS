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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('prefix')->nullable();
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('otp')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1 - Active 0- In-Active,2 = deceased');
            $table->tinyInteger('user_type')->default(1)->comment('1 - HOF,2 - MEMBER');
            $table->string('country_code')->nullable();
            $table->string('phone')->nullable();
            $table->string('member_code')->nullable();
            $table->string('family_code')->nullable();
            $table->text('avatar')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('delete_by')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('created_By')->references('id')->on('users')->nullable();
            $table->foreign('updated_by')->references('id')->on('users')->nullable();
            $table->foreign('delete_by')->references('id')->on('users')->nullable();
            $table->foreign('parent_id')->references('id')->on('users')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
