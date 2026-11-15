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
        Schema::create('template_customizations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedTinyInteger('template_id')->default(1); // Base template (1-10)
            $table->string('name')->nullable(); // Custom name for this customization
            $table->boolean('is_active')->default(false);
            
            // Colors
            $table->string('primary_color')->nullable(); // Main brand color
            $table->string('secondary_color')->nullable();
            $table->string('accent_color')->nullable();
            $table->string('background_color')->nullable();
            $table->string('text_color')->nullable();
            $table->string('link_color')->nullable();
            
            // Typography
            $table->string('heading_font')->nullable(); // e.g., 'Poppins', 'Roboto'
            $table->string('body_font')->nullable();
            $table->string('font_size_base')->nullable(); // e.g., '16px'
            
            // Layout
            $table->string('layout_style')->nullable(); // 'centered', 'left-aligned', 'full-width'
            $table->string('card_style')->nullable(); // 'rounded', 'square', 'minimal'
            $table->boolean('show_shadows')->default(true);
            $table->boolean('show_borders')->default(false);
            
            // Sections visibility
            $table->boolean('show_bio')->default(true);
            $table->boolean('show_socials')->default(true);
            $table->boolean('show_address')->default(true);
            $table->boolean('show_shop')->default(true);
            
            // Custom CSS (advanced)
            $table->text('custom_css')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['user_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('template_customizations');
    }
};
