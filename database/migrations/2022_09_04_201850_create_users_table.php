<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_class_id')->nullable();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('image')->nullable();
            $table->string('social_id')->nullable();
            $table->string('social_name', 50)->nullable();
            $table->string('device_token')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('user_class_id')->references('id')->on('user_classes')->onDelete('cascade')->onUpdate('cascade');
            // $table->foreignId('user_class_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
