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
            $table->string('first_name');
            $table->string('last_name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->integer('reach')->unsigned()->default(0);
            $table->integer('count')->unsigned()->default(0);
            $table->enum('type',['personal','business'])->default('personal');
            $table->boolean('status')->nullable()->default(1);
            $table->boolean('terms')->nullable()->default(1);
            $table->date('expiry')->nullable();
            $table->boolean('card')->nullable()->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
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



  // $table->string('instagram')->nullable();
            // $table->string('twitter')->nullable();
            // $table->string('facebook')->nullable();
            // $table->string('google')->nullable();
            // $table->string('linkedin')->nullable();
            // $table->string('youtube')->nullable();
            // $table->string('tiktok')->nullable();
            // $table->string('pinterest')->nullable();
