<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('template_customizations', function (Blueprint $table) {
            $table->longText('layout_json')->nullable()->after('custom_css');
            $table->json('design_settings')->nullable()->after('layout_json');
        });
    }

    public function down()
    {
        Schema::table('template_customizations', function (Blueprint $table) {
            $table->dropColumn(['layout_json', 'design_settings']);
        });
    }
};
