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
        Schema::create('custom_sections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('customization_id')->nullable(); // Link to specific customization or null for all
            $table->string('section_type'); // 'banner', 'team', 'services', 'testimonials', 'gallery', 'cta', 'features', 'about'
            $table->string('title')->nullable();
            $table->text('subtitle')->nullable();
            $table->text('content')->nullable(); // JSON or HTML content
            $table->string('background_color')->nullable();
            $table->string('text_color')->nullable();
            $table->string('background_image')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('settings')->nullable(); // Additional settings per section type
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('customization_id')->references('id')->on('template_customizations')->onDelete('cascade');
            $table->index(['user_id', 'customization_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_sections');
    }
};
