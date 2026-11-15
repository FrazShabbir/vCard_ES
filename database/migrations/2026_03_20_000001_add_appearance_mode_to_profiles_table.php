<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->string('appearance_mode', 20)->default('template')->after('customization_id');
            // template = use template_id (1-10); custom = use active TemplateCustomization + dynamic view
        });
    }

    public function down()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropColumn('appearance_mode');
        });
    }
};
