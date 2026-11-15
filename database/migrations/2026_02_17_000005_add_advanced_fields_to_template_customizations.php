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
        Schema::table('template_customizations', function (Blueprint $table) {
            // Advanced design fields
            $table->string('border_radius')->nullable()->after('card_style');
            $table->string('section_spacing')->nullable()->after('border_radius');
            $table->string('heading_size')->nullable()->after('font_size_base');
            $table->string('line_height')->nullable()->after('heading_size');
            $table->string('background_gradient')->nullable()->after('background_color');
            $table->string('primary_gradient')->nullable()->after('primary_color');
            $table->boolean('enable_animations')->default(false)->after('show_borders');
            $table->string('animation_style')->nullable()->after('enable_animations');
            $table->text('advanced_css')->nullable()->after('custom_css');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('template_customizations', function (Blueprint $table) {
            $table->dropColumn([
                'border_radius',
                'section_spacing',
                'heading_size',
                'line_height',
                'background_gradient',
                'primary_gradient',
                'enable_animations',
                'animation_style',
                'advanced_css'
            ]);
        });
    }
};
